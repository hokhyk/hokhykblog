<template>
  <div>
    <div>
      <main class="container">
        <div class="sidebar">
          <button class="nav-btn"></button>
        </div>

        <header class="header">
          <img src="~@/assets/images/logo.png" alt="Nexter logo" class="header__logo">
          <h3 class="heading-3">Your own home:</h3>
          <h1 class="heading-1">The ultimate personal freedom</h1>
          <button class="btn header__btn">View our properties</button>
          <div class="header__seenon-text">Seen on</div>
          <div class="header__seenon-logos">
            <img src="~@/assets/images/logo-bbc.png" alt="Seen on logo 1">
            <img src="~@/assets/images/logo-forbes.png" alt="Seen on logo 2">
            <img src="~@/assets/images/logo-techcrunch.png" alt="Seen on logo 3">
            <img src="~@/assets/images/logo-bi.png" alt="Seen on logo 4">
          </div>
        </header>

        <div class="realtors">
          <h3 class="heading-3">Top 3 Realtors</h3>
          <div class="realtors__list">
            <img src="~@/assets/images/realtor-1.jpeg" alt="Realtor 1" class="realtors__img">
            <div class="realtors__details">
              <h4 class="heading-4 heading-4--light">Erik Feinman</h4>
              <p class="realtors__sold">245 houses sold</p>
            </div>

            <img src="~@/assets/images/realtor-2.jpeg" alt="Realtor 2" class="realtors__img">
            <div class="realtors__details">
              <h4 class="heading-4 heading-4--light">Kim Brown</h4>
              <p class="realtors__sold">212 houses sold</p>
            </div>

            <img src="~@/assets/images/realtor-3.jpeg" alt="Realtor 3" class="realtors__img">
            <div class="realtors__details">
              <h4 class="heading-4 heading-4--light">Toby Ramsey</h4>
              <p class="realtors__sold">198 houses sold</p>
            </div>
          </div>
        </div>

        <div>
          <home-nav></home-nav>
        </div>

        <footer class="footer">
          <ul class="nav">
            <li class="nav__item"><a href="#" class="nav__link">Find your dream home</a></li>
            <li class="nav__item"><a href="#" class="nav__link">Request proposal</a></li>
            <li class="nav__item"><a href="#" class="nav__link">Download home planner</a></li>
            <li class="nav__item"><a href="#" class="nav__link">Contact us</a></li>
            <li class="nav__item"><a href="#" class="nav__link">Submit your property</a></li>
            <li class="nav__item"><a href="#" class="nav__link">Come work with us!</a></li>
          </ul>
          <p class="copyright">
            &copy;  2019 by Hok.
          </p>
        </footer>
      </main>
    </div>
  </div>
</template>

<script>

import ResizeMixin from './mixin/ResizeHandler'
import { HomeNav } from './components'

export default {
  name: 'Home',
  components: {
    HomeNav
  },
  mixins: [ResizeMixin],
  computed: {
    device() {
      return this.$store.state.app.device
    },
    fixedHeader() {
      return this.$store.state.settings.fixedHeader
    },
    classObj() {
      return {
        mobile: this.device === 'mobile'
      }
    }
  },
  methods: {
  }
}
</script>

<style lang="scss" scoped>
  @import "~@/styles/home/main.scss";

</style>
