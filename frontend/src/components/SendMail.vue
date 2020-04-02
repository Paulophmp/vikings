<template>
  <v-form
          ref="form">
    <v-card>
    <v-card-title>
      <span class="headline black--text font-weight-bold">Enviar E-mail</span>
    </v-card-title>
    <v-col cols="12" sm="10">
      <v-text-field
              v-model="sendmail.assunto"
              name="assunto"
              label="Assunto"
              :rules="assuntoRules"
              outlined
      ></v-text-field>
    </v-col>

    <v-col cols="12" sm="12">
      <v-textarea
              v-model="sendmail.mensagem"
              outlined
              name="mensagem"
              label="Mensagem"
              :rules="mensagemRules"
              value=""
      ></v-textarea>
    </v-col>
    <v-divider/>
    <v-card-actions>
      <v-spacer/>
      <v-btn
              color="primary"
              text
              @click="reset"
      >
        limpar
      </v-btn>
      <v-btn
              :loading="loading"
              @click="validate"
      >
        Enviar
      </v-btn>
    </v-card-actions>
  </v-card>
  </v-form>
</template>

<script>
import {mapActions} from "vuex";

export default {
  name: 'SendMail',
  props: {
    text: {
      type: String,
      default: ''
    }
  },
  data: () => ({
    loading: false,
    sendmail: {
      assunto: '',
      mensagem: '',
    },
    assuntoRules: [
      v => !!v || 'Assunto is required',
    ],
    mensagemRules: [
      v => !!v || 'Mensagem is required',
    ],
  }),
  methods: {
    ...mapActions({
      cartorioSendEmail: 'cartorio/cartorioSendEmail',
      clienteAction: 'cartorio/clienteAction',
    }),
      validate () {
        if (this.$refs.form.validate()) {
          this.loading = true;
          this.cartorioSendEmail(this.sendmail)
                  .then((data) => {
                    console.log(data);
                    this.loading = false;
                  })
                  .finally(() => {
                    this.loading = false;
                    this.clienteAction();
                  });
        }
    },
    reset () {
      this.$refs.form.reset();
    },
  },
};
</script>
