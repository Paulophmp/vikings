<template>
     <v-card>
        <v-card-title>
          <span v-if="typeForm === 'create'"
                  class="headline black--text font-weight-bold" >Cadastrar</span>
          <span v-else
                  class="headline black--text font-weight-bold" >Editar</span>
        </v-card-title>
        <v-card-text>
          <v-container>
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

                          <v-col cols="14" sm="6">
                              <v-text-field
                                      v-model="cadastrar.email"
                                      label="Email"
                                      outlined
                              ></v-text-field>
                          </v-col>

                          <v-col cols="14" sm="4">
                              <v-text-field
                                      v-model="cadastrar.telefone"
                                      label="Telefone"
                                      v-mask="maskTelefone"
                                      outlined
                              ></v-text-field>
                          </v-col>

                          <v-col cols="8" sm="3">
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
                                      @change="changedValue"
                              >
                              </v-select>
                          </v-col>
                          <v-col cols="10" sm="4">
                              <v-text-field
                                      v-model="cadastrar.documento"
                                      :counter="maskSize"
                                      ref="documento"
                                      v-mask="maskBase"
                                      :rules="maskRules"
                                      :label="maskLabel"
                                      outlined
                              ></v-text-field>
                          </v-col>

                              <v-col cols="8" sm="2">
                                  <v-text-field
                                          v-model="cadastrar.cep"
                                          label="CEP"
                                          outlined
                                          v-mask="maskCep"
                                          counter="9"
                                          ref="cep"
                                          :rules="cepRules"
                                          @keyup="searchCep"
                                          @keydown="searchCep($event)"
                                          required
                                  ></v-text-field>
                              </v-col>
                                  <v-layout  v-if="cadastrar.cep != null && cadastrar.cep.length === 9">

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
                                      label="Status"
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
</template>
<script>
    import {mapActions, mapGetters} from 'vuex';
    import axios from 'axios';
    import {mask} from 'vue-the-mask'
    import cnpjFilter from '../filters/cnpj-cpf';

    export default {
        name: 'Formulario',
        directives: {mask},
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
            maskCep: '#####-###',
            maskTelefone: '(##)####-####',
            maskCPF: '###.###.###-##',
            maskCNPJ: '##.###.###/####-##',
            maskBase: '',
            maskLabel: null,
            maskRules: [],
            maskSize: null,
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
                localidade: '',
                ativo: '', //active : '',
                telefone: '',
                email: ''
            },

            nameRules: [
                v => !!v || 'Name is required',
                v => (v && v.length >= 5) || 'Name must be less than 10 characters',
            ],
            cpfRules: [
                v => !!v || 'CPF is required',
                v => (v && v.length >= 14) || 'CPF must be less than 14 characters',
            ],
            cnpjRules: [
                v => !!v || 'CNPJ is required',
                v => (v && v.length >= 18) || 'CNPJ must be less than 18 characters',
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
        }),
        computed: {
            ...mapGetters({
                clientesGetter: 'cartorio/clientesGetter',
            }),
        },
        watch: {},
        filters: {
            cnpjFilter,
        },
        mounted() {
            this.populated();
        },
        methods: {
            ...mapActions({
                clienteCadastrar: 'cartorio/clienteCadastrar',
                editarCliente: 'cartorio/editarCliente',
                clienteAction: 'cartorio/clienteAction',
            }),

            async populated() {
                if (this.item) {
                    console.log('sDISDBSdgbsvasdvy');
                    this.cadastrar.id = this.item.id;

                    this.cadastrar.nome = this.item.nome;
                    this.cadastrar.documento = this.item.documento;
                    this.cadastrar.razao = this.item.razao;
                    this.cadastrar.tipo_documento = this.item.tipo_documento;
                    this.cadastrar.cep = this.item.cep;
                    this.cadastrar.logradouro = this.item.logradouro;
                    this.cadastrar.nome_tabeliao = this.item.nome_tabeliao;
                    this.cadastrar.bairro = this.item.bairro;
                    this.cadastrar.email = this.item.email;
                    this.cadastrar.telefone = this.item.telefone;
                    this.cadastrar.localidade = this.item.localidade;
                    this.cadastrar.uf = this.item.uf;
                    this.cadastrar.ativo = (this.item.ativo === 0 ? "0" : "1");

                    this.changedValue(this.cadastrar.tipo_documento);
                    this.searchCep();
                }
            },
            searchCep () {
                if(this.cadastrar.cep != null && this.cadastrar.cep.length == 9) {
                    axios.get(`https://viacep.com.br/ws/${ this.cadastrar.cep }/json/`)
                            .then( response => {
                            this.showResults (response.data)
                        })
                }
            },
            showResults(address) {
                if (address.erro){
                    this.menssageError('CEP não encontrado!');
                    return;
                }
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

            },
            reset () {
                this.$refs.form.reset();
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
            },
            changedValue: function(value) {
                if (value === '1') {
                    this.maskBase = this.maskCPF;
                    this.maskLabel = 'CPF';
                    this.maskRules = this.cpfRules;
                    this.maskSize = 14;
                }

                if (value === '2') {
                    this.maskBase = this.maskCNPJ;
                    this.maskLabel = 'CNPJ';
                    this.maskRules = this.cnpjRules;
                    this.maskSize = 18;
                }
            },
        }
    }
</script>
