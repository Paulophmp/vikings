<template>
  <v-layout
          row
          justify-center>
    <v-container fill-height>
      <v-layout
              row
              justify-center
              align-center
      >
        <v-progress-circular
                indeterminate
                :size="70"
                :width="7"
                color="primary">
        </v-progress-circular>
      </v-layout>
    </v-container>
    <h3
            v-html="text"
    />
  </v-layout>
</template>

<script>
export default {
  name: 'Carregando',
  props: {
    text: {
      type: String,
      default: ''
    }
  }
};
</script>
