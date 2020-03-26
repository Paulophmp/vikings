<template>
    <v-dialog v-model="dialog"
              max-width="1500">
      <template v-slot:activator="{ on }">
        <v-btn
                @click.stop="dialog = true"
                class="mx-2" fab dark small color="primary">
          <v-icon dark  v-on="on">mdi-plus</v-icon>
        </v-btn>
      </template>

      <v-card>
        <v-card-title>
          <span class="headline">Cadastrar</span>
        </v-card-title>
        <v-card-text>
          <v-container>

                  <v-form>
                      <v-container>
                          <v-row>
                              <v-col cols="12" sm="6">
                                  <v-text-field
                                          v-model="cadastrar.nome"
                                          label="Nome"
                                          outlined
                                  ></v-text-field>
                              </v-col>

                              <v-col cols="10" sm="4">
                                  <v-text-field
                                          v-model="cadastrar.cpf"
                                          label="CPF"
                                          outlined
                                  ></v-text-field>
                              </v-col>
                              <v-col cols="8" sm="2">
                                  <v-text-field
                                          v-model="cadastrar.cep"
                                          label="CEP"
                                          outlined
                                          counter="8"
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
                                                  box
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
                                                  box
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
                                                  box
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
                                                  box
                                                  outlined
                                          ></v-text-field>
                                      </v-col>
                                  </v-layout>
                              <v-col cols="12" sm="6">
                                  <v-text-field
                                          v-model="cadastrar.email"
                                          ref="email"
                                          label="Email"
                                          outlined
                                  ></v-text-field>
                              </v-col>

                              <v-col cols="12" sm="6">
                                  <v-select
                                          v-model="cadastrar.status"
                                          :items="items"
                                          label="Tipo de ambiente"
                                          item-text="label"
                                          item-value="id"
                                          required
                                          outlined
                                          clear-icon="clear"
                                  >
                                  </v-select>
                              </v-col>

                          </v-row>
                      </v-container>
                  </v-form>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="dialog = false">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>
<script>
    import axios from 'axios'
    export default {
        data: () => ({
            items: [
                { id: '0', label: 'Inativo' },
                { id: '1', label: 'Ativo' },
            ],
            dialog: false,
            first: 'John',
            last: 'Doe',

            cadastrar: {
                nome: '',
                cpf: '',
                logradouro: '',
                complemento: '',
                bairro: '',
                localidade: '',
                numero: '',
                uf: '',
                cep : '',
                email : '',
                status: '',
            },
        }),
        methods: {
            searchCep () {
                if(this.cadastrar.cep != null && this.cadastrar.cep.length == 8) {
                    axios.get(`https://viacep.com.br/ws/${ this.cadastrar.cep }/json/`)
                        .then( response => {
                            this.showResults (response.data)
                        })
                        .catch( error => console.log(error) )
                }
            },
            showResults(address) {
                this.cadastrar.logradouro = address.logradouro;
                this.cadastrar.bairro = address.bairro;
                this.cadastrar.localidade = address.localidade;
                this.cadastrar.uf = address.uf;
                this.$refs.email.focus();
            }
        }
    }
</script>