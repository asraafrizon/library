<template>
    <div>
        <form @submit.prevent="createKoleksi" type="post" v-show="!editState">
            <div class="form-group" :class="{'has-error': errors.jurnal}">
                <input type="text" v-model="koleksi.jurnal" placeholder="Jurnal" class="form-control">
                <span class="help-block" v-for="error in errors.jurnal" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group" :class="{'has-error': errors.tahun}">
                <input type="text" v-model="koleksi.tahun" placeholder="Tahun" class="form-control">
                <span class="help-block" v-for="error in errors.tahun" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group" :class="{'has-error': errors.jumlah}">
                <input type="text" v-model="koleksi.jumlah" placeholder="Jumlah" class="form-control">
                <span class="help-block" v-for="error in errors.jumlah" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group pull-right">
                <button  v-if="add" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

        <form @submit.prevent="updateKoleksi" type="post" v-show="editState">
            <div class="form-group" :class="{'has-error': errorsEdit.jurnal}">
                <input type="text" v-model="koleksiEdit.jurnal" placeholder="Jurnal" class="form-control" autofocus>
                <span class="help-block" v-for="error in errorsEdit.jurnal" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group" :class="{'has-error': errorsEdit.tahun}">
                <input type="text" v-model="koleksiEdit.tahun" placeholder="Tahun" class="form-control">
                <span class="help-block" v-for="error in errorsEdit.tahun" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group" :class="{'has-error': errorsEdit.jumlah}">
                <input type="text" v-model="koleksiEdit.jumlah" placeholder="jumlah" class="form-control">
                <span class="help-block" v-for="error in errorsEdit.jumlah" :key="error.key"> {{error}} </span>
            </div>
            <div class="form-group pull-right">
                <button type="submit" class="btn btn-primary" @click="kemana()">Update</button>
                <button type="button" class="btn btn-default" @click="batalEdit">Batal</button>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Jurnal</th>
                    <th>Tahun</th>
                    <th>Jumlah</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="koleksi in koleksis" :key="koleksi.key" ref="kesini">
                    <td> {{koleksi.jurnal}} </td>
                    <td> {{koleksi.tahun}} </td>
                    <td> {{koleksi.jumlah}} </td>
                    <td>
                        <button type="button" class="btn btn-info" @click="getEdit(koleksi),setFocus()">Edit</button>
                        <button type="button" class="btn btn-danger" @click="hapusKoleksi(koleksi)">Delete</button>
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
            koleksi: {
                jurnal: '',
                tahun: '',
                jumlah: ''
            },
            koleksis: [],
            editState : false,
            koleksiEdit: {
                jurnal: '',
                tahun: '',
                jumlah: ''
            },
            errors: [],
            errorsEdit: [],
            links: [],
            meta: [],
            add: true,
            update: false,
            edit: false,
            hapus: false,
        };
    },

    created() {
        this.fetchKoleksi();
    },

    methods: {
        fetchKoleksi() {
            axios.get('/koleksis').then(respo => {
                this.koleksis = respo.data.data;
                this.links = respo.data.links;
                this.meta = respo.data.meta;
            });
        },
        createKoleksi(){
            axios.post('/koleksis', this.koleksi).then(respon => {
                this.koleksis.push(respon.data.koleksi);
                this.koleksi = {
                    jurnal: '',
                    tahun: '',
                    jumlah: ''
                };
                // swal('Added', 'Berhasil ditambahkan', 'success' );
                swal({
                    title: 'Added!',
                    text: 'Berhasil ditambahkan',
                    type: 'success',
                    confirmButtonText: 'okelah',
                    position: 'top',

                })
                this.add = true;
                this.errors = [];
            }).catch(respon => {
                this.errors = respon.response.data.errors;
            });
        },

        getEdit(edit){
            this.editState = true;
            this.koleksiEdit = edit;
            this.edit = true;
        },

        batalEdit(){
            this.editState = false;
            this.koleksiEdit = {
                jurnal: '',
                tahun: '',
                jumlah: ''
            };
        },

        updateKoleksi(){
            axios.patch('/koleksis/'+this.koleksiEdit.id, this.koleksiEdit).then(respon => {
                this.editState = false;
                this.koleksiEdit = {
                    jurnal: '',
                    tahun: '',
                    jumlah: ''
                };
                swal({
                    title: 'Updated!',
                    text: 'Berhasil di Update',
                    type: 'warning',
                    confirmButtonText: 'okelah',
                    position: 'top',

                })
                this.update = true;
                this.errorsEdit = [];
            }).catch(respon => {
                this.errorsEdit = respon.response.data.errors;
            });
        },
        hapusKoleksi(koleksi){
            axios.delete('/koleksis/'+koleksi.id).then(respo => {
                var index = this.koleksis.indexOf(koleksi);
                this.koleksis.splice(index,1);
              });   

      },

      prevPage() {
        axios.get(this.links.prev+'').then(respo => {
            this.layanans = respo.data.data;
            this.links = respo.data.links;
            this.meta = respo.data.meta;
        })
    },

    nextPage() {
        axios.get(this.links.next+'').then(respo => {
            this.layanans = respo.data.data;
            this.links = respo.data.links;
            this.meta = respo.data.meta;
        })
    },

    setFocus: function() {
      // Note, you need to add a ref="search" attribute to your input.
      this.$refs.search.focus();
  },

  kemana: function() {
      // Note, you need to add a ref="search" attribute to your input.
      this.$refs.kesini.focus();
  }

}

}
</script>
<style type="lang" scoped></style>
