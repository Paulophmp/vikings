<template>
    <v-container>
<!--        <v-form-->
<!--                enctype="multipart/form-data"-->
<!--                ref="form"-->
<!--                files="true"-->
<!--                lazy-validation-->
<!--                method="post"-->
<!--        >-->
            <v-row>
                <v-col cols="12" sm="4">
                    <v-file-input
                            v-model="file"
                            color="blue-grey accent-4"
                            accept=".xml"
                            counter
                            label="File input Xml"
                            dense
                            placeholder="Selecione seu arquivo XML"
                            prepend-icon="mdi-paperclip"
                            outlined
                            :show-size="1000"
                            @change="fileValidation"
                            id="file"
                            ref="files"
                            name="file"
                            >
                            <template v-slot:selection="{ index, text }">
                                <v-chip
                                        color="blue-grey accent-4"
                                        dark
                                        label
                                        small
                                >
                                    {{ text }}
                                </v-chip>
                            </template>
                    </v-file-input>
                </v-col>
                <template v-if="file">
                    <v-btn
                            :loading="loading1"
                            :disabled="loading1"
                            color="blue-grey"
                            class="ma-2 white--text"
                            @click="submit"
                    >
                        Upload
                        <v-icon right dark>mdi-cloud-upload</v-icon>
                    </v-btn>
                </template>
                <template v-if="clientesGetter.dados.length > 0">
                    <v-btn
                        :loading="loading"
                        :disabled="loading"
                        color="grey darken-3"
                        class="ma-2 white--text"
                        @click="download"
                    >
                        Download
                        <v-icon right dark>mdi-download</v-icon>
                    </v-btn>
                </template>
            </v-row>
<!--        </v-form>-->
    </v-container>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';

    export default {
        data () {
            return {
                file: null,
                loader: null,
                loading1: false,
                loading: false,
            }
        },
        computed: {
            ...mapGetters({
                clientesGetter: 'cartorio/clientesGetter',
            }),
        },
        methods : {
            ...mapActions({
                clienteCadastrarImport: 'cartorio/clienteCadastrarImport',
                clienteDownload: 'cartorio/clienteDownload',
                clienteAction: 'cartorio/clienteAction',
            }),
            fileValidation() {
                let file = this.file;

                if (file.type != 'text/xml') {
                    this.loading = false;

                }
            },
            submit () {
                this.loading1 = true;
                this.clienteCadastrarImport(this.file)
                    .then((data) => {
                        console.log(data);
                        this.clienteAction();
                        this.file = null;
                    })
                    .finally(() => {
                        this.loading1 = false;
                    });
            },

            download () {
                this.clienteDownload().then(() => {
                    this.loading = false;
                });
            }
        },

    }
</script>
