const ZH_MESSAGES = {
  authExpired: '登录状态已失效，请刷新页面后重新登录。',
  forbidden: '您暂无此操作权限，请联系管理员开通。',
  notFound: '请求地址不存在，请刷新页面后重试。',
  tooManyRequests: '请求过于频繁，请稍后再试。',
  timeout: '请求超时，请检查网络后重试。',
  network: '网络连接异常，请检查网络后重试。',
  serverError: '服务器开小差了，请稍后重试。',
  default: '请求失败，请稍后重试。',
};

const EN_MESSAGES = {
  authExpired: 'Your login has expired. Refresh and sign in again.',
  forbidden: 'You do not have permission for this action. Contact an admin.',
  notFound: 'The requested endpoint was not found. Refresh and try again.',
  tooManyRequests: 'Too many requests. Please try again later.',
  timeout: 'Request timed out. Check your connection and try again.',
  network: 'Network connection failed. Check your connection and try again.',
  serverError: 'Server is temporarily unavailable. Please try again later.',
  default: 'Request failed. Please try again later.',
};

function getMessageMap(locale) {
  const normalized = String(locale || '').toLowerCase();
  return normalized.startsWith('zh') ? ZH_MESSAGES : EN_MESSAGES;
}

function getStatus(error) {
  const status = error && error.response ? error.response.status : null;
  return Number.isFinite(status) ? status : null;
}

function getRawMessage(error) {
  const responseData = error && error.response ? error.response.data : null;

  if (typeof responseData === 'string' && responseData.trim()) {
    return responseData.trim();
  }

  if (responseData && typeof responseData === 'object') {
    if (typeof responseData.message === 'string' && responseData.message.trim()) {
      return responseData.message.trim();
    }

    if (typeof responseData.error === 'string' && responseData.error.trim()) {
      return responseData.error.trim();
    }
  }

  if (error && typeof error.message === 'string' && error.message.trim()) {
    return error.message.trim();
  }

  return '';
}

function isTimeoutError(error, rawMessage) {
  const code = String((error && error.code) || '').toUpperCase();
  const lowerMessage = String(rawMessage || '').toLowerCase();

  return code === 'ECONNABORTED' ||
    lowerMessage.includes('timeout') ||
    lowerMessage.includes('timed out');
}

function isNetworkError(error, rawMessage) {
  const code = String((error && error.code) || '').toUpperCase();
  const lowerMessage = String(rawMessage || '').toLowerCase();
  const networkCodes = ['ERR_NETWORK', 'ENOTFOUND', 'ECONNREFUSED', 'EAI_AGAIN'];

  return networkCodes.includes(code) ||
    lowerMessage.includes('network error') ||
    lowerMessage.includes('network request failed') ||
    lowerMessage.includes('failed to fetch');
}

function hasAuthKeyword(rawMessage) {
  const lowerMessage = String(rawMessage || '').toLowerCase();
  const authKeywords = [
    'unauthorized',
    'unauthenticated',
    'forbidden',
    'token expired',
    'token invalid',
    'invalid token',
    'csrf token mismatch',
    'login required',
    '请先登录',
    '未登录',
    '登录过期',
    '登录失效',
    'token过期',
    'token 过期',
    '无权限',
    '鉴权失败',
    '认证失败',
  ];

  return authKeywords.some(keyword => lowerMessage.includes(keyword));
}

function shouldPromptLoginRedirect(error) {
  const status = getStatus(error);
  if (status === 401 || status === 419) {
    return true;
  }

  if (status === 403) {
    return false;
  }

  const rawMessage = getRawMessage(error);
  return hasAuthKeyword(rawMessage);
}

function shouldShowNginxAlert(error) {
  const status = getStatus(error);
  const responseData = error && error.response ? error.response.data : null;

  return status === 404 &&
    typeof responseData === 'string' &&
    responseData.toLowerCase().includes('nginx');
}

function resolveHttpErrorMessage(error, options = {}) {
  const messages = getMessageMap(options.locale);
  const status = getStatus(error);
  const rawMessage = getRawMessage(error);

  if (status === 401 || status === 419) {
    return messages.authExpired;
  }

  if (status === 403) {
    return messages.forbidden;
  }

  if (status === 404) {
    return messages.notFound;
  }

  if (status === 429) {
    return messages.tooManyRequests;
  }

  if (status !== null && status >= 500) {
    return messages.serverError;
  }

  if (status === 422 && rawMessage) {
    return rawMessage;
  }

  if (isTimeoutError(error, rawMessage)) {
    return messages.timeout;
  }

  if (isNetworkError(error, rawMessage)) {
    return messages.network;
  }

  if (hasAuthKeyword(rawMessage)) {
    return messages.authExpired;
  }

  if (rawMessage) {
    return rawMessage;
  }

  return messages.default;
}

module.exports = {
  resolveHttpErrorMessage,
  shouldShowNginxAlert,
  shouldPromptLoginRedirect,
};
