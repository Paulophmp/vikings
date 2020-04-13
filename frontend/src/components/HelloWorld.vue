<template>
  <div>
      <v-toolbar
              color="grey darken-3"
              dark
              extended
              flat
      >
          <v-spacer></v-spacer>
          <div style="width:500%;margin-top:15px;float:left;padding:8% 0 5%;text-align:center;">
              <a
                  href="https://github.com/Paulophmp/vikings"
                  target="_blank">
                  <img src="https://pngimage.net/wp-content/uploads/2018/06/vikings-serie-png-6.png" style="max-width:55%;width:210px">
              </a>
          </div>
      </v-toolbar>
      <v-container>
        <v-card
                class="mx-auto"
                max-width="1200"
                style="margin-top: -18px;"
        >
          <v-toolbar flat>
            <v-toolbar-title class="black--text font-weight-bold">Cadastro de Cartórios</v-toolbar-title>
            <v-spacer></v-spacer>
              <template>
                  <v-btn class="ma-2 black--text" fab dark small color="#FFF" @click="sendMailr">
                      <v-icon>mdi-email-outline</v-icon>
                  </v-btn>
                  <v-btn class="mx-2" fab dark small color="grey darken-4" @click="cadastrar">
                      <v-icon dark>mdi-plus</v-icon>
                  </v-btn>
              </template>
          </v-toolbar>
          <v-divider></v-divider>
            <div v-if="loading">
                <Carregando :text="'Cartórios Cadastrados'"></Carregando>
            </div>
            <v-card v-else>
                <v-card-title>
                    <v-col cols="12" sm="6">
                        <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                chips
                                multiple
                                solo
                                clearable
                        ></v-text-field>
                    </v-col>
                </v-card-title>
              <v-container>
                <v-data-table
                        :headers="headers"
                        :items="clientesGetter.dados"
                        sort-by="calories"
                        class="elevation-1"
                        :search="search"
                        :itemsDocumento="itemsDocumento"
                >
                  <template v-slot:item.ativo="{ item }">
                      <v-btn v-if="item.ativo === 1" x-small color="grey darken-3" dark>{{ item.ativo | tipoStatus }}</v-btn>
                      <v-btn v-else x-small color="red lighten-2" dark>{{ item.ativo | tipoStatus }}</v-btn>
                  </template>

                  <template v-slot:item.tipo_documento="{ item }">
                      <v-btn v-if="item.tipo_documento === '1'" x-small color="primary" dark>{{ item.tipo_documento | tipoDocumento }}</v-btn>
                      <v-btn v-else x-small color="grey darken-3" dark>{{ item.tipo_documento | tipoDocumento }}</v-btn>
                  </template>

                    <template v-slot:item.action="{ item }">
                        <v-icon
                                small
                                class="mr-2"
                                @click="showItem(item)"
                        >
                            mdi-eye
                        </v-icon>
                            <v-icon
                                    small
                                    class="mr-2"
                                    @click="editItem(item)"
                            >
                                mdi-pencil
                            </v-icon>
                        <v-icon
                                small
                                class="mr-2"
                                @click="deletarItem(item)"
                                itemIdParaExclusao = "item.id"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                    <v-alert
                            slot="no-results"
                            :value="true"
                            color="grey lighten-2"
                            icon="mdi-alert-outline">
                        Sua busca por "{{ search }}" não encontrou nenhum resultado.
                    </v-alert>
                </v-data-table>
                  <template>
                      <v-col align="center">
                          <v-card
                                  color="grey lighten-4"
                          >
                              <ImportXml></ImportXml>
                          </v-card>
                      </v-col>
                  </template>
              </v-container>
            </v-card>
        </v-card>
      </v-container>

      <v-dialog v-model="dialog" max-width="1180px">
          <v-card>
              <formulario :key="editedItem.id" type-form="edite" :item="editedItem"></formulario>
          </v-card>
      </v-dialog>
      <v-dialog v-model="dialogCadastar" max-width="1180px">
          <v-card>
              <formulario :key="0"></formulario>
          </v-card>
      </v-dialog>
      <v-dialog v-model="dialogSendMailr" max-width="800px">
          <v-card>
              <SendMail :key="1"></SendMail>
          </v-card>
      </v-dialog>
      <v-dialog
              v-model="dialogVisual"
              @keydown.esc="dialog = false"
              max-width="1290px">
          <v-card>
              <v-card-text class="pl-5">
                  <v-container
                          grid-list-md
                          text-xs-left>
                      <v-layout
                              justify-space-around
                              row
                              wrap>
                          <v-flex
                                  lg12
                                  dark
                                  class="text-xs-left">
                              <b><h4>DADOS DO CARTÓRIO</h4></b>
                              <v-divider class="pb-2"/>
                          </v-flex>
                          <v-flex>
                              <p><b>Nome do Cartório</b></p>
                              {{ itemEmVisualizacao.nome }}
                              <p />
                          </v-flex>
                          <v-flex>
                              <p><b>Razão</b></p>
                              <p>
                                  {{ itemEmVisualizacao.razao }}
                              </p>
                          </v-flex>
                          <v-flex>
                              <p><b>Nome do Tabelião</b></p>
                              <p>
                                  {{ itemEmVisualizacao.nome_tabeliao }}
                              </p>
                          </v-flex>
                          <v-flex>
                              <p><b>Tipo do Documento</b></p>
                              <p v-if="itemEmVisualizacao.tipo_documento === '1'">
                                  CPF
                              </p>
                              <p v-else>
                                  CNPJ
                              </p>
                          </v-flex>
                          <v-flex>
                              <p><b>Número do documento</b></p>
                              <p>
                                  {{ itemEmVisualizacao.documento | cnpjFilter }}
                              </p>
                          </v-flex>
                          <v-flex>
                              <p><b>Status</b></p>
                              <p v-if="itemEmVisualizacao.ativo === 1">
                                  Ativo
                              </p>
                              <p v-else>
                                  Inativo
                              </p>
                          </v-flex>
                      </v-layout>
                      <v-layout
                              justify-space-around
                              row
                              wrap>
                          <v-flex
                                  lg12
                                  dark
                                  class="text-xs-left">
                              <b><h4>ENDEREÇO</h4></b>
                              <v-divider class="pb-2"/>
                          </v-flex>
                          <v-flex>
                              <p><b>Logradouro</b></p>
                              <p>
                                  {{ itemEmVisualizacao.logradouro }}
                              </p>
                          </v-flex>
                          <v-flex>
                              <p><b>Bairro</b></p>
                              <p>
                                  {{ itemEmVisualizacao.bairro }}
                              </p>
                          </v-flex>
                          <v-flex>
                              <p><b>Localidade</b></p>
                              <p>
                                  {{ itemEmVisualizacao.localidade }}
                              </p>
                          </v-flex>
                          <v-flex>
                              <p><b>UF</b></p>
                              <p>
                                  {{ itemEmVisualizacao.uf }}
                              </p>
                          </v-flex>
                          <v-flex>
                              <p><b>CEP</b></p>
                              <p>
                                  {{ itemEmVisualizacao.cep }}
                              </p>
                          </v-flex>

                      </v-layout>
                  </v-container>
              </v-card-text>
              <v-divider/>
              <v-card-actions>
                  <v-spacer/>
                  <v-btn
                          color="grey darken-3"
                          text
                          @click="dialogVisual = false"
                  >
                      Fechar
                  </v-btn>
              </v-card-actions>
          </v-card>
      </v-dialog>


   </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Formulario from './Formulario';
  import ImportXml from './ImportXml';
  import Carregando from './Carregando';
  import SendMail from './SendMail';
  import cnpjFilter from '@/filters/cnpj-cpf';


  export default {
    components: {
        Formulario,
        ImportXml,
        Carregando,
        SendMail
    },
    data: () => ({

        itemIdParaExclusao: null,
        dialog: false,
        dialogCadastar: false,
        dialogSendMailr: false,
        dialogVisual: false,
        loading: true,
        editedItem: '',
        cor: 'primary',
        itemEmVisualizacao: {},
        search: "",
        // items: ['CPF', 'CNPJ'],
        value: ['CPF', 'CNPJ'],

        itemsDocumento: [
            { id: '1', label: 'CPF' },
            { id: '2', label: 'CNPJ' },
        ],
        headers: [
            {
              text: 'Nome Cartório',
              align: 'start',
              value: 'nome',
            },
            { text: 'Tipo Documento', value: 'tipo_documento' },
            { text: 'Tabelião', value: 'nome_tabeliao' },
            { text: 'Status', value: 'ativo' },
            { text: 'Ações', value: 'action', sortable: false },
         ],
    }),
    filters: {
      cnpjFilter,
      tipoStatus(id) {
        let tipoStatus = '';
        switch (id) {
          case 1:
            tipoStatus = 'Ativo';
            break;
          case 0:
            tipoStatus = 'Inativo';
            break;
          default:
              tipoStatus = '';
        }
        return tipoStatus;
      },
        tipoDocumento(id) {
            let tipoDocumento = '';
            switch (id) {
                case '1':
                    tipoDocumento = 'CPF¹' ;
                    break;
                case '2':
                    tipoDocumento = 'CNPJ²';
                    break;
                default:
                    tipoDocumento = '';
            }
            return tipoDocumento;
        },
    },

     mounted() {
      this.clienteAction();
    },

    computed: {
      ...mapGetters({
            clientesGetter: 'cartorio/clientesGetter',
      }),
    },
    watch: {
        clientesGetter() {
            this.loading = false;
        },

        dialog(val) {
            if (!val) {
                // this.clienteAction();
            }
        }
    },
    methods: {
      ...mapActions({
          clienteAction: 'cartorio/clienteAction',
          excluirClienteAction: 'cartorio/excluirClienteAction',
      }),
        editItem(item) {
            console.log('editItem', item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        showItem(item) {
            this.itemEmVisualizacao = Object.assign({}, item);
            this.dialogVisual = true;
        },
        deletarItem(item) {
            const index = this.clientesGetter.dados.indexOf(item);
            this.excluirClienteAction(item.id) && this.clientesGetter.dados.splice(index, 1);
        },
        cadastrar() {
            this.dialogCadastar = true;
        },
        sendMailr() {
            this.dialogSendMailr = true;
        },
    },
  }
</script>
