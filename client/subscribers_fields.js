function buildUrl (url) {
  return "http://127.0.0.1:8000/" + url;
}

const app = new Vue({
  el: '#app',
  data: {
    results: [],
    subscribers: [],
    fields: [],
    subscriberFieldData: {
      id: null,
      subscriber: null,
      field: null
    }
  },
  mounted () {
    this.initSelect();
    this.getSubscribers();
    this.getFields();
  },
  methods: {
    initSelect() {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    },
    getSubscribers() {
      let url = buildUrl('subscriber');
 
      axios.get(url).then((response) => {
        this.subscribers = response.data;
      }).catch( error => { console.log(error); });
    },
    getFields() {
      let url = buildUrl('field');
 
      axios.get(url).then((response) => {
        this.fields = response.data;
      }).catch( error => { console.log(error); });
    },
    postSubscriberField() {
      let url = buildUrl('field_subscriber');
      let fieldSubscriber = this.subscriberFieldData;
 
      axios.post(url, fieldSubscriber).then((response) => {
        this.handleSubscriberFields();       
      }).catch( error => { console.log(error); });
    },
    deleteSubscriberField(id) {
      let url = buildUrl('field_subscriber/'+ id);

      axios.delete(url).then((response) => {
        this.handleSubscriberFields();
      }).catch( error => { console.log(error); });
    },
    getSubscriberField(subscriber){
      let url = buildUrl('field_subscriber/'+ subscriber);

      axios.get(url).then((response) => {
        this.results = response.data;
      }).catch( error => { 
        this.results = null;
        // console.log(error); 
      });
    },

    handleSubscriberFields(){
      let subscriber = document.getElementById("subscriber").value;
      this.getSubscriberField(subscriber);
    }
  },

  computed: {
    processedSubscribers() {
      let fields = this.results;
      return fields;
    },
  }
});