function buildUrl (url) {
  return "http://127.0.0.1:8000/" + url;
}

const app = new Vue({
  el: '#app',
  data: {
    results: [],
    subscriberData: {
      id: null,
      email: '',
      name: '',
      state: 1,
    },
  },
  mounted () {
    this.initSelect();
    this.getSubscribers();
  },
  methods: {
    initSelect() {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    },
    getSubscribers() {
      let url = buildUrl('subscriber');
 
      axios.get(url).then((response) => {
        this.results = response.data;
      }).catch( error => { console.log(error); });
    },
    save(){
      if(this.subscriberData.id) {
        this.putSubscriber();
      } else {
        this.postSubscriber();
      }
    },
    postSubscriber() {
      let url = buildUrl('subscriber');
      let subscribers = this.subscriberData;
 
      axios.post(url, subscribers).then((response) => {
        this.getSubscribers();
        this.subscriberData = {
          id: null,
          email: '',
          name: '',
          state: 1,
        }
      }).catch( error => { console.log(error); });
    },
    putSubscriber() {
      let subscribers = this.subscriberData;
      let url = buildUrl('subscriber/'+subscribers.id);
 
      axios.put(url, subscribers).then((response) => {
        this.getSubscribers();
        this.subscriberData = {
          id: null,
          email: '',
          name: '',
          state: 1,
        }
      }).catch( error => { console.log(error); });
    },
    deleteSubscriber(id) {
      let url = buildUrl('subscriber/'+ id);

      axios.delete(url).then((response) => {
        this.getSubscribers();
      }).catch( error => { console.log(error); });
    },
    getSubscriber(id){
      let url = buildUrl('subscriber/'+ id);

      axios.get(url).then((response) => {
        this.subscriberData = response.data[0];
        document.getElementById('label-name').classList.add('active');
        document.getElementById('label-email').classList.add('active');
      }).catch( error => { console.log(error); });
    },
  },

  computed: {
    processedSubscribers() {
      let subscribers = this.results;
      return subscribers;
    }
  }
});