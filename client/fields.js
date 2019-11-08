function buildUrl (url) {
  return "http://127.0.0.1:8000/" + url;
}

const app = new Vue({
  el: '#app',
  data: {
    results: [],
    fieldData: {
      id: null,
      title: '',
      type: 1,
    },
  },
  mounted () {
    this.initSelect();
    this.getFields();
  },
  methods: {
    initSelect() {
      var elems = document.querySelectorAll('select');
      var instances = M.FormSelect.init(elems);
    },
    getFields() {
      let url = buildUrl('field');
 
      axios.get(url).then((response) => {
        this.results = response.data;
      }).catch( error => { console.log(error); });
    },
    save(){
      if(this.fieldData.id) {
        this.putField();
      } else {
        this.postField();
      }
    },
    postField() {
      let url = buildUrl('field');
      let field = this.fieldData;
 
      axios.post(url, field).then((response) => {
        this.getFields();
        this.fieldData = {
          id: null,
          title: '',
          type: 1,
        };
      }).catch( error => { console.log(error); });
    },
    putField() {
      let field = this.fieldData;
      let url = buildUrl('field/'+field.id);
 
      axios.put(url, field).then((response) => {
        this.getFields();
        this.fieldData = {
          id: null,
          title: '',
          type: 1,
        };
      }).catch( error => { console.log(error); });
    },
    deleteField(id) {
      let url = buildUrl('field/'+ id);

      axios.delete(url).then((response) => {
        this.getFields();
      }).catch( error => { console.log(error); });
    },
    getField(id){
      let url = buildUrl('field/'+ id);

      axios.get(url).then((response) => {
        this.fieldData = response.data[0];
        document.getElementById('label-title').classList.add('active');
      }).catch( error => { console.log(error); });
    },
  },

  computed: {
    processedFields() {
      let fields = this.results;
      return fields;
    }
  }
});