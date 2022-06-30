var Editor = (function () {
    'use strict';

    /*! *****************************************************************************
    Copyright (c) Microsoft Corporation. All rights reserved.
    Licensed under the Apache License, Version 2.0 (the "License"); you may not use
    this file except in compliance with the License. You may obtain a copy of the
    License at http://www.apache.org/licenses/LICENSE-2.0

    THIS CODE IS PROVIDED ON AN *AS IS* BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
    KIND, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY IMPLIED
    WARRANTIES OR CONDITIONS OF TITLE, FITNESS FOR A PARTICULAR PURPOSE,
    MERCHANTABLITY OR NON-INFRINGEMENT.

    See the Apache Version 2.0 License for specific language governing permissions
    and limitations under the License.
    ***************************************************************************** */

    var __assign = function() {
        __assign = Object.assign || function __assign(t) {
            for (var s, i = 1, n = arguments.length; i < n; i++) {
                s = arguments[i];
                for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p)) t[p] = s[p];
            }
            return t;
        };
        return __assign.apply(this, arguments);
    };

    /**
     * Copyright (c) 2018-present, Ephox, Inc.
     *
     * This source code is licensed under the Apache 2 license found in the
     * LICENSE file in the root directory of this source tree.
     *
     */
    var validEvents = [
        'onActivate',
        'onAddUndo',
        'onBeforeAddUndo',
        'onBeforeExecCommand',
        'onBeforeGetContent',
        'onBeforeRenderUI',
        'onBeforeSetContent',
        'onBeforePaste',
        'onBlur',
        'onChange',
        'onClearUndos',
        'onClick',
        'onContextMenu',
        'onCopy',
        'onCut',
        'onDblclick',
        'onDeactivate',
        'onDirty',
        'onDrag',
        'onDragDrop',
        'onDragEnd',
        'onDragGesture',
        'onDragOver',
        'onDrop',
        'onExecCommand',
        'onFocus',
        'onFocusIn',
        'onFocusOut',
        'onGetContent',
        'onHide',
        'onInit',
        'onKeyDown',
        'onKeyPress',
        'onKeyUp',
        'onLoadContent',
        'onMouseDown',
        'onMouseEnter',
        'onMouseLeave',
        'onMouseMove',
        'onMouseOut',
        'onMouseOver',
        'onMouseUp',
        'onNodeChange',
        'onObjectResizeStart',
        'onObjectResized',
        'onObjectSelected',
        'onPaste',
        'onPostProcess',
        'onPostRender',
        'onPreProcess',
        'onProgressState',
        'onRedo',
        'onRemove',
        'onReset',
        'onSaveContent',
        'onSelectionChange',
        'onSetAttrib',
        'onSetContent',
        'onShow',
        'onSubmit',
        'onUndo',
        'onVisualAid'
    ];
    var isValidKey = function (key) { return validEvents.map(function (event) { return event.toLowerCase(); }).indexOf(key.toLowerCase()) !== -1; };
    var bindHandlers = function (initEvent, listeners, editor) {
        Object.keys(listeners)
            .filter(isValidKey)
            .forEach(function (key) {
            var handler = listeners[key];
            if (typeof handler === 'function') {
                if (key === 'onInit') {
                    handler(initEvent, editor);
                }
                else {
                    editor.on(key.substring(2), function (e) { return handler(e, editor); });
                }
            }
        });
    };
    var bindModelHandlers = function (ctx, editor) {
        var modelEvents = ctx.$props.modelEvents ? ctx.$props.modelEvents : null;
        var normalizedEvents = Array.isArray(modelEvents) ? modelEvents.join(' ') : modelEvents;
        editor.on(normalizedEvents ? normalizedEvents : 'change input undo redo', function () {
            ctx.$emit('input', editor.getContent({ format: ctx.$props.outputFormat }));
        });
    };
    var initEditor = function (initEvent, ctx, editor) {
        var value = ctx.$props.value ? ctx.$props.value : '';
        var initialValue = ctx.$props.initialValue ? ctx.$props.initialValue : '';
        editor.setContent(value || (ctx.initialized ? ctx.cache : initialValue));
        // Always bind the value listener in case users use :value instead of v-model
        ctx.$watch('value', function (val, prevVal) {
            if (editor && typeof val === 'string' && val !== prevVal && val !== editor.getContent({ format: ctx.$props.outputFormat })) {
                editor.setContent(val);
            }
        });
        // checks if the v-model shorthand is used (which sets an v-on:input listener) and then binds either
        // specified the events or defaults to "change keyup" event and emits the editor content on that event
        if (ctx.$listeners.input) {
            bindModelHandlers(ctx, editor);
        }
        bindHandlers(initEvent, ctx.$listeners, editor);
        ctx.initialized = true;
    };
    var unique = 0;
    var uuid = function (prefix) {
        var time = Date.now();
        var random = Math.floor(Math.random() * 1000000000);
        unique++;
        return prefix + '_' + random + unique + String(time);
    };
    var isTextarea = function (element) {
        return element !== null && element.tagName.toLowerCase() === 'textarea';
    };
    var normalizePluginArray = function (plugins) {
        if (typeof plugins === 'undefined' || plugins === '') {
            return [];
        }
        return Array.isArray(plugins) ? plugins : plugins.split(' ');
    };
    var mergePlugins = function (initPlugins, inputPlugins) {
        return normalizePluginArray(initPlugins).concat(normalizePluginArray(inputPlugins));
    };
    var isNullOrUndefined = function (value) { return value === null || value === undefined; };

    /**
     * Copyright (c) 2018-present, Ephox, Inc.
     *
     * This source code is licensed under the Apache 2 license found in the
     * LICENSE file in the root directory of this source tree.
     *
     */
    var createState = function () {
        return {
            listeners: [],
            scriptId: uuid('tiny-script'),
            scriptLoaded: false
        };
    };
    var CreateScriptLoader = function () {
        var state = createState();
        var injectScriptTag = function (scriptId, doc, url, callback) {
            var scriptTag = doc.createElement('script');
            scriptTag.referrerPolicy = 'origin';
            scriptTag.type = 'application/javascript';
            scriptTag.id = scriptId;
            scriptTag.src = url;
            var handler = function () {
                scriptTag.removeEventListener('load', handler);
                callback();
            };
            scriptTag.addEventListener('load', handler);
            if (doc.head) {
                doc.head.appendChild(scriptTag);
            }
        };
        var load = function (doc, url, callback) {
            if (state.scriptLoaded) {
                callback();
            }
            else {
                state.listeners.push(callback);
                if (!doc.getElementById(state.scriptId)) {
                    injectScriptTag(state.scriptId, doc, url, function () {
                        state.listeners.forEach(function (fn) { return fn(); });
                        state.scriptLoaded = true;
                    });
                }
            }
        };
        // Only to be used by tests.
        var reinitialize = function () {
            state = createState();
        };
        return {
            load: load,
            reinitialize: reinitialize
        };
    };
    var ScriptLoader = CreateScriptLoader();

    /**
     * Copyright (c) 2018-present, Ephox, Inc.
     *
     * This source code is licensed under the Apache 2 license found in the
     * LICENSE file in the root directory of this source tree.
     *
     */
    var getGlobal = function () { return (typeof window !== 'undefined' ? window : global); };
    var getTinymce = function () {
        var global = getGlobal();
        return global && global.tinymce ? global.tinymce : null;
    };

    /**
     * Copyright (c) 2018-present, Ephox, Inc.
     *
     * This source code is licensed under the Apache 2 license found in the
     * LICENSE file in the root directory of this source tree.
     *
     */
    var editorProps = {
        apiKey: String,
        cloudChannel: String,
        id: String,
        init: Object,
        initialValue: String,
        inline: Boolean,
        modelEvents: [String, Array],
        plugins: [String, Array],
        tagName: String,
        toolbar: [String, Array],
        value: String,
        disabled: Boolean,
        tinymceScriptSrc: String,
        outputFormat: {
            type: String,
            validator: function (prop) { return prop === 'html' || prop === 'text'; }
        },
    };

    /**
     * Copyright (c) 2018-present, Ephox, Inc.
     *
     * This source code is licensed under the Apache 2 license found in the
     * LICENSE file in the root directory of this source tree.
     *
     */
    var renderInline = function (h, id, tagName) {
        return h(tagName ? tagName : 'div', {
            attrs: { id: id }
        });
    };
    var renderIframe = function (h, id) {
        return h('textarea', {
            attrs: { id: id },
            style: { visibility: 'hidden' }
        });
    };
    var initialise = function (ctx) { return function () {
        var finalInit = __assign(__assign({}, ctx.$props.init), { readonly: ctx.$props.disabled, selector: "#" + ctx.elementId, plugins: mergePlugins(ctx.$props.init && ctx.$props.init.plugins, ctx.$props.plugins), toolbar: ctx.$props.toolbar || (ctx.$props.init && ctx.$props.init.toolbar), inline: ctx.inlineEditor, setup: function (editor) {
                ctx.editor = editor;
                editor.on('init', function (e) { return initEditor(e, ctx, editor); });
                if (ctx.$props.init && typeof ctx.$props.init.setup === 'function') {
                    ctx.$props.init.setup(editor);
                }
            } });
        if (isTextarea(ctx.element)) {
            ctx.element.style.visibility = '';
            ctx.element.style.display = '';
        }
        getTinymce().init(finalInit);
    }; };
    var Editor = {
        props: editorProps,
        created: function () {
            this.elementId = this.$props.id || uuid('tiny-vue');
            this.inlineEditor = (this.$props.init && this.$props.init.inline) || this.$props.inline;
            this.initialized = false;
        },
        watch: {
            disabled: function () {
                this.editor.setMode(this.disabled ? 'readonly' : 'design');
            }
        },
        mounted: function () {
            this.element = this.$el;
            if (getTinymce() !== null) {
                initialise(this)();
            }
            else if (this.element && this.element.ownerDocument) {
                var channel = this.$props.cloudChannel ? this.$props.cloudChannel : '5';
                var apiKey = this.$props.apiKey ? this.$props.apiKey : 'no-api-key';
                var scriptSrc = isNullOrUndefined(this.$props.tinymceScriptSrc) ?
                    "https://cdn.tiny.cloud/1/" + apiKey + "/tinymce/" + channel + "/tinymce.min.js" :
                    this.$props.tinymceScriptSrc;
                ScriptLoader.load(this.element.ownerDocument, scriptSrc, initialise(this));
            }
        },
        beforeDestroy: function () {
            if (getTinymce() !== null) {
                getTinymce().remove(this.editor);
            }
        },
        deactivated: function () {
            var _a;
            if (!this.inlineEditor) {
                this.cache = this.editor.getContent();
                (_a = getTinymce()) === null || _a === void 0 ? void 0 : _a.remove(this.editor);
            }
        },
        activated: function () {
            if (!this.inlineEditor && this.initialized) {
                initialise(this)();
            }
        },
        render: function (h) {
            return this.inlineEditor ? renderInline(h, this.elementId, this.$props.tagName) : renderIframe(h, this.elementId);
        }
    };

    /**
     * Copyright (c) 2018-present, Ephox, Inc.
     *
     * This source code is licensed under the Apache 2 license found in the
     * LICENSE file in the root directory of this source tree.
     *
     */

    return Editor;

}());
