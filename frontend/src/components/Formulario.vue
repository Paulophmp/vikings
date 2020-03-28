<template>
<!--    <v-dialog v-model="dialog" max-width="1500">-->
<!--      <template v-slot:activator="{ on }">-->
<!--        <v-btn-->
<!--                @click.stop="dialog = true"-->
<!--                class="mx-2" fab dark small color="primary">-->
<!--          <v-icon dark  v-on="on">mdi-plus</v-icon>-->
<!--        </v-btn>-->
<!--      </template>-->

      <v-card>
        <v-card-title>
          <span v-if="typeForm === 'create'"
                  class="headline" >Cadastrar</span>
          <span v-else
                  class="headline" >Editar</span>
        </v-card-title>
        <v-card-text>
          <v-container>
<!--              {{ clientesGetter }}-->
                  <v-form
                          enctype="multipart/form-data"
                          ref="form"
                          files="true"
                          lazy-validation
                  >
                      <v-row>
                          <v-col cols="10" sm="4">
                              <v-text-field
                                      v-model="cadastrar.nome"
                                      :rules="nameRules"
                                      label="Nome do cartório"
                                      outlined
                              ></v-text-field>
                          </v-col>

                          <v-col cols="10" sm="4">
                              <v-text-field
                                      v-model="cadastrar.razao"
                                      :rules="nameRules"
                                      label="Razão Social"
                                      outlined
                              ></v-text-field>
                          </v-col>

                              <v-col cols="10" sm="4">
                                  <v-text-field
                                          v-model="cadastrar.nome_tabeliao"
                                          :rules="nameRules"
                                          label="Nome tabelião"
                                          outlined
                                  ></v-text-field>
                              </v-col>

                          <v-col cols="9" sm="3">
                              <v-select
                                      v-model="cadastrar.tipo_documento"
                                      :items="itemsDocumento"
                                      label="Tipo de documento"
                                      :rules="tipoRules"
                                      item-text="label"
                                      item-value="id"
                                      required
                                      outlined
                                      clear-icon="clear"
                              >
                              </v-select>
                          </v-col>
                          <v-col cols="10" sm="4">
                              <v-text-field
                                      v-model="cadastrar.documento"
                                      :counter="14"
                                      ref="documento"
                                      :rules="cpfRules"
                                      label="Documento"
                                      outlined
                              ></v-text-field>
                          </v-col>

                              <v-col cols="8" sm="2">
                                  <v-text-field
                                          v-model="cadastrar.cep"
                                          label="CEP"
                                          outlined
                                          counter="9"
                                          :rules="cepRules"
                                          @keyup="searchCep"
                                          @keydown="searchCep($event)"
                                          required
                                  ></v-text-field>
                              </v-col>
                                  <v-layout  v-if="cadastrar.cep != null && cadastrar.cep.length === 8">

                                  <v-col cols="8" sm="4"
                                  >
                                      <v-text-field
                                              label="Endereço"
                                              v-model="cadastrar.logradouro"
                                              filled
                                              required
                                              outlined
                                      ></v-text-field>
                                  </v-col>

                                  <v-col cols="6" sm="4"
                                  >
                                      <v-text-field
                                              label="Bairro"
                                              v-model="cadastrar.bairro"
                                              ref="numeroCep"
                                              required
                                              filled
                                              outlined
                                      ></v-text-field>
                                  </v-col>

                                  <v-col cols="4" sm="2"
                                  >
                                      <v-text-field
                                              label="Cidade"
                                              v-model="cadastrar.localidade"
                                              ref="numeroCep"
                                              required
                                              filled
                                              outlined
                                      ></v-text-field>
                                  </v-col>

                                  <v-col cols="2" sm="2"
                                  >
                                      <v-text-field
                                              label="UF"
                                              v-model="cadastrar.uf"
                                              ref="numeroCep"
                                              required
                                              filled
                                              outlined
                                      ></v-text-field>
                                  </v-col>
                              </v-layout>

                          <v-col cols="9" sm="3">
                              <v-select
                                      v-model="cadastrar.ativo"
                                      :items="items"
                                      ref="ativo"
                                      label="Tipo de ambiente"
                                      :rules="tipoRules"
                                      item-text="label"
                                      item-value="id"
                                      required
                                      outlined
                                      clear-icon="clear"
                              >
                              </v-select>
                          </v-col>

                      </v-row>
                  </v-form>
              <v-snackbar
                      v-model="snackbar"
                      :multi-line="mode === 'multi-line'"
                      right
                      :timeout="timeout"
                      top
                      :color="statusSnackBar"
                      :vertical="mode === 'vertical'"
              >
                  {{ text }}
                  <v-btn

                          @click="snackbar = false"
                  >
                      <v-icon>mdi-close-circle</v-icon>
                  </v-btn>
              </v-snackbar>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
            <div class="text-xs-right">
                <v-btn color="blue darken-1" text @click="reset">Limpar</v-btn>
                <v-btn
                    v-if="typeForm === 'create'"
                    color="blue darken-1"
                    text
                    @click="validate"
                    :loading="loading"
                >Salvar
                </v-btn>
                <v-btn
                        v-else
                        @click="atualizar"
                        :loading="loading"
                >
                    Atualizar
                </v-btn>
            </div>

        </v-card-actions>
      </v-card>
<!--    </v-dialog>-->
</template>
<script>
    import {mapActions, mapGetters} from 'vuex';
    import axios from 'axios'
    export default {
        name: 'Formulario',
        props: {
            typeForm: {
                default: 'create',
                type: String,
            },
            item: {
                default: () => {},
                type: Object,
            },
         },
        data: () => ({
            items: [
                { id: '0', label: 'Inativo' },
                { id: '1', label: 'Ativo' },
            ],
            itemsDocumento: [
                { id: '1', label: 'CPF' },
                { id: '2', label: 'CNPJ' },
            ],
            valid: true,
            dialog: false,
            loading: false,
            statusSnackBar: '',
            snackbar: false,
            y: 'top',
            x: null,
            mode: '',
            timeout: 2500,
            text: '',

            cadastrar: {
                id: '',
                nome: '', //name: '',
                documento: '', //document: '', //cpf
                razao: '', //cep: '', //cep
                tipo_documento: '', //address: '', // logradouro
                cep: '', //district: '', // bairro
                logradouro: '', //city: '', // localidade
                nome_tabeliao: '', //state: '', // UF
                bairro: '', //telephone: '',
                localidade: '', //email : '',
                ativo: '', //active : '',
            },
            nameRules: [
                v => !!v || 'Name is required',
                v => (v && v.length >= 5) || 'Name must be less than 10 characters',
            ],
            cpfRules: [
                v => !!v || 'CPF is required',
                v => (v && v.length <= 14) || 'CPF must be less than 10 characters',
            ],
            cepRules: [
                v => !!v || 'CEP is required',
                v => (v && v.length <= 9) || 'CPF must be less than 9 characters',
            ],
            tipoRules: [
                v => !!v || 'Tipo de ambiente is required',
            ],
            emailRules: [
                v => !!v || 'E-mail is required',
            ],
            telephoneRules: [
                v => !!v || 'Telefone is required',
            ],
        }),
        computed: {
            ...mapGetters({
                clientesGetter: 'cartorio/clientesGetter',
            }),
        },
        watch: {
            item(value) {
                this.cadastrar.id = value.id;
                this.cadastrar.nome = value.nome;
                this.cadastrar.documento = value.documento;
                this.cadastrar.razao = value.razao;
                this.cadastrar.tipo_documento = value.tipo_documento;
                this.cadastrar.cep = value.cep;
                this.cadastrar.logradouro = value.logradouro;
                this.cadastrar.nome_tabeliao = value.nome_tabeliao;
                this.cadastrar.bairro = value.bairro;
                this.cadastrar.localidade = value.localidade;
                this.cadastrar.uf = value.uf;
                this.cadastrar.ativo = (value.ativo === 0 ? "0" : "1");
            },
        },
        filters: {
            tipoStatus(id) {
                let tipoStatus = '';
                switch (id) {
                    case 1:
                        tipoStatus = '';
                        break;
                    case 0:
                        tipoStatus = 'Inativo';
                        break;
                    default:
                        tipoStatus = '';
                }
                return tipoStatus;
            },
        },
        methods: {
            ...mapActions({
                clienteCadastrar: 'cartorio/clienteCadastrar',
                editarCliente: 'cartorio/editarCliente',
                clienteAction: 'cartorio/clienteAction',
            }),
            searchCep () {
                if(this.cadastrar.cep != null && this.cadastrar.cep.length == 8) {
                    axios.get(`https://viacep.com.br/ws/${ this.cadastrar.cep }/json/`)
                        .then( response => {
                            this.showResults (response.data)
                        })
                        .catch(
                            this.menssageError('Cep não encontrado!')
                        )
                }
            },
            showResults(address) {
                console.log('address', address);
                this.cadastrar.logradouro = address.logradouro;
                this.cadastrar.bairro = address.bairro;
                this.cadastrar.localidade = address.localidade;
                this.cadastrar.uf = address.uf;
                this.$refs.ativo.focus();
            },
            validate () {
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.clienteCadastrar(this.cadastrar)
                        .then((data) => {
                            console.log(data);
                            this.clienteAction();
                            this.loading = false;
                    })
                        .finally(() => {
                            this.menssageSuccess('Cadastrado com sucesso!');
                            this.loading = false;
                            this.dialog = false;
                        });
                }

                // this.loading1 = true;
                // this.clienteCadastrarImport(this.file)
                //     .then((data) => {
                //         console.log(data);
                //         this.clienteAction();
                //         this.file = null;
                //     })
                //     .finally(() => {
                //         this.loading1 = false;
                //     });
            },
            reset () {
                this.$refs.form.reset()
            },
            menssageSuccess(text){
                this.statusSnackBar = 'success';
                this.text = text;
                this.snackbar = true;
            },
            menssageError(text = 'Erro ao cadastrar!'){
                this.statusSnackBar = 'error';
                this.text = text;
                this.snackbar = true;
            },
            atualizar() {
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.editarCliente(this.cadastrar).then(() => {
                        this.loading = false;
                    });
                } else {
                    this.menssageError();
                }
            }
        }
    }
</script>
