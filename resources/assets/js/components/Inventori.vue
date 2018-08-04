<template>
    <div>
        <form  @submit.prevent="createInventori" type="post" v-show="!editState">
            <div class="form-group" :class="{'has-error':errors.tahun}">
                <input type="text" v-model="inventori.tahun" placeholder="Tahun" class="form-control">
                <span class="help-block" v-for="error in errors.tahun" :key="error.key">{{error}}</span>
            </div>
            <div class="form-group" :class="{'has-error':errors.fakultas}">
                <input type="text" v-model="inventori.fakultas" placeholder="Fakultas" class="form-control">
                <span class="help-block" v-for="error in errors.fakultas" :key="error.key">{{error}}</span>
            </div>
            <div class="form-group" :class="{'has-error':errors.judul_qty}">
                <input type="text" v-model="inventori.judul_qty" placeholder="Judul_qty"class="form-control">
                <span class="help-block" v-for="error in errors.judul_qty" :key="error.key">{{error}}</span>
            </div>
            <div class="form-group" :class="{'has-error':errors.eksemplar_qty}">
                <input type="text" v-model="inventori.eksemplar_qty" placeholder="Eksemplar_qty" class="form-control">
                <span class="help-block" v-for="error in errors.eksemplar_qty" :key="error.key">{{error}}</span>
            </div>
            <div class="form-group pull-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <form  @submit.prevent="updateInventori" type="post" v-show="editState">
            <div class="form-group" :class="{'has-error': errorsEdit.tahun}">
                <input type="text" v-model="inventoriEdit.tahun" placeholder="Tahun" class="form-control">
                <span class="help-block" v-for="error in errorsEdit.tahun" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group" :class="{'has-error': errorsEdit.fakultas}">
                <input type="text" v-model="inventoriEdit.fakultas" placeholder="Fakultas" class="form-control">
                <span class="help-block" v-for="error in errorsEdit.fakultas" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group" :class="{'has-error': errorsEdit.judul_qty}">
                <input type="text" v-model="inventoriEdit.judul_qty" placeholder="Judul_qty"class="form-control">
                <span class="help-block" v-for="error in errorsEdit.judul_qty" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group" :class="{'has-error': errorsEdit.eksemplar_qty}">
                <input type="text" v-model="inventoriEdit.eksemplar_qty" placeholder="Eksemplar_qty" class="form-control">
                <span class="help-block" v-for="error in errorsEdit.eksemplar_qty" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group pull-right">
                <button type="submit" class="btn btn-info">Update</button>
                <button type="button" class="btn btn-danger" @click="batalEdit(inventori)">Batal</button>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                   <th>Tahun</th>
                   <th>Fakultas</th>
                   <th>Judul QTY</th>
                   <th>Eksemplar QTY</th>
                   <td>Action</td>
               </tr>
           </thead>
           <tbody>
            <tr v-for="inventori in inventoris" :key="inventori.key">
                <td> {{inventori.tahun}} </td>
                <td> {{inventori.fakultas}} </td>
                <td> {{inventori.judul_qty}} </td>
                <td> {{inventori.eksemplar_qty}} </td>
                <td>
                    <button type="button" class="btn btn-info" @click="getEdit(inventori)">Edit</button>
                    <button type="button" class="btn btn-danger" @click="hapusInventori(inventori)">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
    <div>
        <div class="pull-right">
            <ul class="pagination">
                <li><button type="button" class="btn btn-default" @click="prevPage" :disabled="!this.links.prev">Prev</button></li>
                <li>Page {{meta.current_page}} of {{meta.last_page}}</li>
                <li><button type="button" class="btn btn-default" @click="nextPage" :disabled="!this.links.next">Next</button></li>
            </ul>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            inventori: {
                tahun: '',
                fakultas: '',
                judul_qty: '',
                eksemplar_qty: ''
            },
            inventoriEdit: {
                tahun: '',
                fakultas: '',
                judul_qty: '',
                eksemplar_qty: ''
            },
            inventoris: [],
            editState: false,
            errors: [],
            errorsEdit: [],
            links: [],
            meta: []
        };
    },

    created(){
        this.fetchInventori();
    },

    methods: {
        fetchInventori(){
            axios.get('/inventori').then(respo => {
                this.inventoris = respo.data.data;
                this.links = respo.data.links;
                this.meta = respo.data.meta;
            });
        },

        createInventori(){
            axios.post('/inventori', this.inventori).then(respon => {
                this.inventoris.push(respon.data.inventori);
                this.inventori = {
                    tahun: '',
                    fakultas: '',
                    judul_qty: '',
                    eksemplar_qty: ''
                };
                this.errors = [];
            }).catch(respon => {
               this.errors = respon.response.data.errors;
            });
        },

        getEdit(edit){
            this.editState = true;
            this.inventoriEdit = edit;
        },

        batalEdit(){
            this.editState = false;
            this.inventoriEdit = {
               tahun: '',
               fakultas: '',
               judul_qty: '',
               eksemplar_qty: ''
           }
       },

       updateInventori(){
        axios.patch('/inventori/'+this.inventoriEdit.id, this.inventoriEdit).then(respon => {
            this.editState = false;
            this.inventoriEdit = {
             tahun: '',
             fakultas: '',
             judul_qty: '',
             eksemplar_qty: ''
         };
         this.errorsEdit = [];
     }).catch(respon => {
        this.errorsEdit = respon.response.data.errors;
    });
 },

 hapusInventori(inventori){
    axios.delete('/inventori/'+inventori.id).then(respo => {
        let index = this.inventoris.indexOf(inventori);
        this.inventoris.splice(index,1);
    });
},

prevPage() {
    axios.get(this.links.prev).then(respo => {
        this.inventoris = respo.data.data;
        this.links = respo.data.links;
        this.meta = respo.data.meta;
    })
},

nextPage() {
    axios.get(this.links.next).then(respo => {
        this.inventoris = respo.data.data;
        this.links = respo.data.links;
        this.meta = respo.data.meta;
    })
}
}
}
</script>