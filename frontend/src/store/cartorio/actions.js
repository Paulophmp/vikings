import * as types from './types';
import * as api from '@/api/cartorio';

export const clienteAction = ({ commit }) => api
    .syncCliente()
    .then(({ data }) => {
        commit(types.SET_CLIENTE, data);
        return data;
    });

export const clienteCadastrar = ({ commit }, params ) => api
    .cadastraCliente(params)
    .then(({ data }) => {
        console.log(params);
        commit(types.SET_CLIENTE, data);
        return data;
    });

export const excluirClienteAction = async ({ commit }, id) => await api
    .syncExcluirCliente(id)
    .then(({ id }) => {
        commit(types.EXCLUIR_CLIENTE, id);
        return id;
    });

export const clienteCadastrarImport = async ({ commit }, params ) => await api
    .cadastraClienteImportXML(params)
    .then(({ data }) => {
        console.log('akiiii', params);
        commit(types.UPLOAD_CLIENTE, data);
        return data;
    });

export const editarCliente=  async ({ commit }, params ) => await api
    .editarClienteApi(params)
    .then(({ data }) => {
        commit(types.EDITAR_CLIENTE, data);
        return data;
    });

export const cartorioSendEmail = ({ commit }, params ) => api
    .sendMail(params)
    .then(({ data }) => {
        console.log("sendEmail",params);
        commit(types.SET_CLIENTE, data);
        return data;
    });

// eslint-disable-next-line no-unused-vars
export const clienteDownload = async ({ commit, dispatch}) => await api
    .clienteDownloadXsl(
        {
            'Accept': `application/xls`,
            'Content-Description': 'File Transfer',
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        })
    .then((data) => {
        console.log('data', data);
        const url = URL.createObjectURL(new Blob([data.data], {
            type: 'application/vnd.ms-excel'
        }));

        console.log('aqui',url);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'fileName.xlsx')
        document.body.appendChild(link);
        link.click();
        // commit(types.DOWNLOAD_CLIENTE, data);
        // return data;
    });

// eslint-disable-next-line no-unused-vars
// export const clienteDownload = async ({ commit, dispatch }) => api
//     .clienteDownloadXsl({'Accept': `application/xls`})
//     .then(({response}) => {
//         console.log(response)
//         const noArquivo = 'cartorio.xls';
//         // console.log(response.headers['content-type']);
//         const type = response.headers['content-type'];
//         const blob = new Blob([response.data], { type: type});
//         // // const blob = new Blob([response.data], { type: 'xlsx', encoding: 'UTF-8' });
//         // const blob = response.data;
//         const url = window.URL.createObjectURL(blob);
//         const link = document.createElement('a');
//         link.href = url;
//         link.setAttribute('download', noArquivo);
//         document.body.appendChild(link);
//         link.click();
//     });

// export const buscar = ({ commit }, params) => api
//     .syncCliente(params)
//     .then(({ data }) => {
//         commit(types.SET_ASSUNTOS, data);
//         return data;
//     });