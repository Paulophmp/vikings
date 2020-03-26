<template>
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
</template>
<script>
    export default {
        name: 'SnackbarMessage',
        props: {
            typeForm: {
                default: 'create',
                type: String,
            },
         },
        data: () => ({
            dialog: false,
            statusSnackBar: '',
            snackbar: false,
            y: 'top',
            x: null,
            mode: '',
            timeout: 2500,
            text: '',

            loading: false,

        }),
        methods: {

        }
    }
</script>
