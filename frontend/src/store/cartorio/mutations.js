import * as types from './types';

export const state = {
    cliente: [],
    excluirCliente: [],
    downloadCliente: {},
    editarCliente: [],
};

export const mutations = {
    [types.SET_CLIENTE](state, params) {
        state.cliente = params;
    },
    [types.DOWNLOAD_CLIENTE](state, params) {
        state.downloadCliente = params;
    },
    [types.EDITAR_CLIENTE](state, params) {
        state.editarCliente = params;
    },
    [types.EXCLUIR_CLIENTE] (state, certificadoId) {
        state.excluirCliente.find( (certificado, index) => {
            if (certificado._id === certificadoId){
                return state.excluirCliente.splice(index, 1);
            }
        });
    }
};