@extends('admin::layouts.master')

@section('title', __('admin/common.forbidden'))
@section('code', '403')
@section('content', __('admin/common.has_no_permission'))
