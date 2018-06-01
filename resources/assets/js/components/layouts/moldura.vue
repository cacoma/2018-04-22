<template>
  <b-card :header="this.Slug2" style="margin: auto; max-width: 80%;">
    <slot></slot>
  </b-card>

</template>
<script>
export default {
  props: ['passed'],
  data () {
    return {
    slug2: racaz.slug2,
    Slug2: racaz.columnName(racaz.pathArray[2]),
  }
}
}

</script>
