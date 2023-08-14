<?php

namespace ComPDFKit\Constant;

class CPDFURL
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';

    const API_V1_OAUTH_TOKEN = 'v1/oauth/token';
    const API_V1_CREATE_TASK = 'v1/task/{executeTypeUrl}';
    const API_V1_TOOL_SUPPORT = 'v1/tool/support';
    const API_V1_FILE_INFO = 'v1/file/fileInfo';
    const API_V1_ASSET_INFO = 'v1/asset/info';
    const API_V1_TASK_LIST = 'v1/task/list';
    const API_V1_UPLOAD_FILE = 'v1/file/upload';
    const API_V1_EXECUTE_TASK = 'v1/execute/start';
    const API_V1_TASK_INFO = 'v1/task/taskInfo';
}