import * as http from './http';

const buildData = (params) => {
    const bodyFormData = new FormData();

    Object.keys(params).forEach((key) => {
        bodyFormData.append(key, params[key]);
    });

    return bodyFormData;
};


export const syncCliente = function () {
    return http.getRequest('/cartorio');
};

export const cadastraCliente = function (params) {
    console.log('-----', {params});
    const path = '/clients/cadastrar/';
    return http.postRequest(path, params);
};


export const syncExcluirCliente = (id) => {
    const path = '/clients/delete';
    console.log('delete', id);
    return http.deleteRequest(path, (id));
};

export const cadastraClienteImportXML = function (params) {
    console.log('teste', params);
    const path = '/clients/cadastrar-xml/';
    return http.postRequest(path, buildData(params));
};

export const editarClienteApi = function (params) {
    console.log('editar', params);
    const path = '/clients/edit/';
    return http.putRequest(path, buildData(params), params.id);
};


export const clienteDownloadXsl = async (headers) => {
    // headers.responseType = 'blob';
    const config = {
        headers: headers,
        responseType: 'blob'
    };
    console.log(config);
    // const path = '/clients/download';
    return await http.getRequest('/clients/download', config);
};