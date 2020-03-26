<template>
  <div>
      <v-toolbar
              color="primary"
              dark
              extended
              flat
      >
          <v-spacer></v-spacer>
          <v-btn
                  href="https://github.com/Paulophmp/Teste-All_blacks"
                  target="_blank"
                  text
          >
              <span class="mr-2">LINK GIT HUB</span>
              <v-icon>mdi-open-in-new</v-icon>
          </v-btn>
      </v-toolbar>
      <v-container>
        <v-card
                class="mx-auto"
                max-width="1200"
                style="margin-top: -75px;"
        >
          <v-toolbar flat>
            <v-toolbar-title class="grey--text">Cadastro de Planilhas</v-toolbar-title>
            <v-spacer></v-spacer>
<!--              <formulario/>-->
          </v-toolbar>
          <v-divider></v-divider>
            <div v-if="loading">
                <Carregando :text="'Clientes Cadastrados'"></Carregando>
            </div>
            <v-card v-else>
              <v-container>
                <v-data-table
                        :headers="headers"
                        :items="clientesGetter"
                        sort-by="calories"
                        class="elevation-1"
                >
                  <template v-slot:item.active="{ item }">
                    <v-chip>{{ item.active | tipoStatus }}</v-chip>
                  </template>
                    <template v-slot:item.action="{ item }">
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
              <formulario type-form="edite" :item="editedItem"></formulario>
          </v-card>
      </v-dialog>
   </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex';
  import Formulario from './Formulario';
  import ImportXml from './ImportXml';
  import Carregando from './Carregando';


  export default {
    components: {
        Formulario,
        ImportXml,
        Carregando
    },
    data: () => ({
        itemIdParaExclusao: null,
        dialog: false,
        loading: true,
        editedItem: '',
        headers: [
            {
              text: 'Nome',
              align: 'start',
              value: 'name',
            },
            { text: 'CPF', value: 'document' },
            { text: 'Cidade', value: 'city' },
            { text: 'Telefone', value: 'telephone' },
            { text: 'E-mail', value: 'email' },
            { text: 'Status', value: 'active' },
            { text: 'Ações', value: 'action', sortable: false },
         ],
    }),
    filters: {
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
    },

     mounted() {
      this.clienteAction();
    },

    computed: {
      ...mapGetters({
            clientesGetter: 'cliente/clientesGetter',
      }),
    },
    watch: {
        clientesGetter() {
            this.loading = false;
        },
    },
    methods: {
      ...mapActions({
          clienteAction: 'cliente/clienteAction',
          excluirClienteAction: 'cliente/excluirClienteAction',
      }),
        editItem(item) {
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        deletarItem(item) {
            const index = this.clientesGetter.indexOf(item);
            // this.excluirClienteAction(item.id);
            this.excluirClienteAction(item.id) && this.clientesGetter.splice(index, 1);
        },
    },
  }
</script>
