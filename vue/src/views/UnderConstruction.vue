<template>
    <v-container
            class="fill-height"
            fluid
    >
        <v-row
                align="center"
                justify="center"
        >
            <v-col
                    cols="6"
                    sm="4"
                    align="center"
            >
                <v-img
                        src="../assets/construction.png"
                        width="150"
                        align="center"
                ></v-img>
                <span class="display-1 font-weight-bold pa-2">
                  Раздел на этапе проектирования
                </span>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
  name: 'UnderConstruction',

  data: () => ({})
}
</script>
