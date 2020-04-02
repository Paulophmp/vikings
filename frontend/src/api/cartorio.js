import * as http from './http';

const buildData = (params, form, namespace) => {
    const bodyFormData = form || new FormData();

    let formKey;
    Object.keys(params).forEach((key) => {
        if (namespace) {
            formKey = '${namespace}[${key}]';
        } else {
            formKey = key;
        }

        if (typeof params[key] === 'object' && !(params[key] instanceof File)) {
            buildData(params[key], bodyFormData, key);
        } else {
            // if it's a string or a File object
            bodyFormData.append(formKey, params[key]);
        }
    });

    return bodyFormData;
};


export const syncCliente = function () {
    return http.getRequest('/');
};

export const cadastraCliente = function (params) {
    console.log('-----', {params});
    const path = '/cartorio/cadastrar/';
    return http.postRequest(path, params);
};


export const syncExcluirCliente = (id) => {
    const path = '/cartorio/delete';
    console.log('delete', id);
    return http.deleteRequest(path, (id));
};

// export const cadastraClienteImportXML = function (params) {
//     console.log('teste', params);
//     const path = '/cartorio/cadastrar-xml/';
//     return http.postRequest(path, buildData(params));
// };

export const cadastraClienteImportXML = function (params) {
    console.log('teste', params);
    const path = '/cartorio/cadastrar-xml/';
    return http.postRequest(path, buildData({ file : params }));
};


export const sendMail = function (params) {
    const path = '/cartorio/sendmail';
    return http.postRequest(path, params);
};

export const editarClienteApi = function (params) {
    const path = '/cartorio/edit';
    return http.putRequest(path, params, params.id);
};


export const clienteDownloadXsl = async (headers) => {
    // headers.responseType = 'blob';
    const config = {
        headers: headers,
        responseType: 'blob'
    };
    console.log(config);
    // const path = '/cartorio/download';
    return await http.getRequest('/cartorio/download', config);
};