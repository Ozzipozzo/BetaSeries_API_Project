<template>
  <div class="home">
      <SerieDisplay
          v-for="item in content"
          :key="item.id"
          :serie="item"
        />

        <button id="myBtn" title="Aller en haut de la page" @click="TopFunction" >Haut de page</button>
        <ul class="pagination">
          <li @click="GetSeriesPagination($event)" class="pagination__dot active" value="0">1</li>
          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="20">2</li>
          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="40">3</li>
          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="60">4</li>
          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="80">5</li>
          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="100">6</li>
          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="120">7</li>
          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="140">8</li>
          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="160">9</li>
          <li @click="GetSeriesPagination($event)" class="pagination__dot" value="180">10</li>
        </ul>
  </div>
</template>


<script>
import SerieDisplay from "../components/SerieDisplay.vue";

export default {
  data() {
    return {
      isLoading: true,
      content: null,
      currentPage: null,
    };
  },
  components: {
    SerieDisplay,
  },
    created() {
        this.GetSeries();
    },
  methods: {
    GetSeries() {
      fetch("http://127.0.0.1:8000/api/show/all", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          start: 0
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data) {
              this.content = data;
          } else {
              alert("Une erreur est survenu !");
          }
        }).catch(function(error) {
            console.log(error);
        });
    },
    GetSeriesPagination(event) {
      fetch("http://127.0.0.1:8000/api/show/all", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          start: event.target.value
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data) {
              // this.content = data;
              // let dotList = document.getElementsByClassName('pagination__dot');
              // dotList.forEach(element => {
              //   element.classList.remove('active');
              // });
              // event.target.classList.add('active');
          } else {
              alert("Une erreur est survenu !");
          }
        }).catch(function(error) {
            console.log(error);
        });
    },
    TopFunction() {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    }
  },
};
</script>

<style>
body {
  overflow-x: hidden;
  font-family: "Manrope", sans-serif;
  background-color: rgb(23, 23, 23);
  color: white;
}
h1, h2, h3, h4, h5 {
  font-family: serif;
}
li {
  list-style-type: none;
}
button {
  cursor: pointer;
}
label {
  cursor: pointer;
}
.home {
  margin: auto;
}
.form {
  box-sizing: border-box;
  width: 320px;
  padding: 18px;
  margin: auto;
  background-color: rgb(29, 27, 27);
  border-radius: 15px;
  box-shadow: 8px 8px 10px rgba(0, 0, 0, 0.4);
  color: white;
}
.form .form-group input {
  margin: 15px 0px;
  width: 100%;
  box-sizing: border-box;
}
.form button {
  margin: 15px 0px;
  width: 100%;
  font-size: 18px;
}
.form a img {
  margin: 15px 0px;
  width: 100%;
}
.pagination {
  display: flex;
  justify-content: center;
}
.pagination li {
  text-align: center;
  cursor: pointer;
  width: 20px;
  height: 20px;
  padding: 10px;
  margin: 10px;
  border-radius: 50%;
  border: solid white 1px;
}
.pagination li.active {
  background-color: #F7D633; 
  color: black;
}
.pagination li:hover, .pagination li:active {
  background-color: black;
}

#myBtn {
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 988;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: #F7D633;
  color: black;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
}

#myBtn:hover {
  color: white;
  background-color: dodgerblue;
}

</style>