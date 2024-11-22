<script setup>
import Header from './components/Header.vue';

</script>

<template>
  <section class="min-h-screen">
    <Header />
    <main class="min-h-screen mt-16 w-full h-full bg-white flex justify-center md:px-40">
      <router-view />
    </main>
  </section>

</template>

<script>
export default {
  name: 'App',
  components: {
    Header,
  },
}
</script>

<style scoped>
/* header {
  line-height: 1.5;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
} */

/* @media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  } 

/* } */
</style>
