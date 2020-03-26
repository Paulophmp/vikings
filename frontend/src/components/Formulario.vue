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
                          <v-col cols="12" sm="6">
                              <v-text-field
                                      v-model="cadastrar.name"
                                      :rules="nameRules"
                                      label="Nome"
                                      outlined
                              ></v-text-field>
                          </v-col>

                              <v-col cols="10" sm="4">
                                  <v-text-field
                                          v-model="cadastrar.document"
                                          :counter="14"
                                          :rules="cpfRules"
                                          label="CPF"
                                          outlined
                                  ></v-text-field>
                              </v-col>
                              <v-col cols="8" sm="2">
                                  <v-text-field
                                          v-model="cadastrar.postcode"
                                          label="CEP"
                                          outlined
                                          counter="9"
                                          :rules="cepRules"
                                          @keyup="searchCep"
                                          @keydown="searchCep($event)"
                                          required
                                  ></v-text-field>
                              </v-col>
                                  <v-layout  v-if="cadastrar.postcode != null && cadastrar.postcode.length === 8">

                                  <v-col cols="8" sm="4"
                                  >
                                      <v-text-field
                                              label="Endereço"
                                              v-model="cadastrar.address"
                                              filled
                                              required
                                              outlined
                                      ></v-text-field>
                                  </v-col>

                                  <v-col cols="6" sm="4"
                                  >
                                      <v-text-field
                                              label="Bairro"
                                              v-model="cadastrar.district"
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
                                              v-model="cadastrar.city"
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
                                              v-model="cadastrar.state"
                                              ref="numeroCep"
                                              required
                                              filled
                                              outlined
                                      ></v-text-field>
                                  </v-col>
                              </v-layout>
                          <v-col cols="12" sm="6">
                              <v-text-field
                                      v-model="cadastrar.email"
                                      ref="email"
                                      label="Email"
                                      :rules="emailRules"
                                      outlined
                              ></v-text-field>
                          </v-col>

                          <v-col cols="9" sm="3">
                              <v-text-field
                                      v-model="cadastrar.telephone"
                                      ref="telephone"
                                      label="Telefone"
                                      :rules="telephoneRules"
                                      outlined
                              ></v-text-field>
                          </v-col>
                          <v-col cols="9" sm="3">
                              <v-select
                                      v-model="cadastrar.active"
                                      :items="items"
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
                name: '',
                document: '', //cpf
                postcode: '', //cep
                address: '', // logradouro
                district: '', // bairro
                city: '', // localidade
                state: '', // UF
                telephone: '',
                email : '',
                active : '',
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
                clientesGetter: 'cliente/clientesGetter',
            }),
        },
        watch: {
            item(value) {
                console.log('AEEEEEEEEEEE',value);
                this.cadastrar.name = value.name;
                this.cadastrar.document = value.document;
                this.cadastrar.postcode = value.postcode;
                this.cadastrar.address = value.address;
                this.cadastrar.district = value.district;
                this.cadastrar.city = value.city;
                this.cadastrar.state = value.state;
                this.cadastrar.telephone = value.telephone;
                this.cadastrar.email = value.email;
                this.cadastrar.active = (value.active === 0 ? "0" : "1");
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
                clienteCadastrar: 'cliente/clienteCadastrar',
                editarCliente: 'cliente/editarCliente',
            }),
            searchCep () {
                if(this.cadastrar.postcode != null && this.cadastrar.postcode.length == 8) {
                    axios.get(`https://viacep.com.br/ws/${ this.cadastrar.postcode }/json/`)
                        .then( response => {
                            this.showResults (response.data)
                        })
                        .catch(
                            this.menssageError('Cep não encontrado!')
                        )
                }
            },
            showResults(address) {
                this.cadastrar.address = address.logradouro;
                this.cadastrar.district = address.bairro;
                this.cadastrar.city = address.localidade;
                this.cadastrar.state = address.uf;
                this.$refs.email.focus();
            },
            validate(){
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.clienteCadastrar(this.cadastrar).then(() => {
                        this.loading = false;
                    });
                } else {
                    this.menssageError();
                }
            },
            reset () {
                this.$refs.form.reset()
            },
            // menssageSuccess(text = 'Cadastrado com sucesso!'){
            //     this.statusSnackBar = 'success';
            //     this.text = text;
            //     this.snackbar = true;
            // },
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
