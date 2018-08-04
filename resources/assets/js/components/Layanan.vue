<template>
	<div>
		
<!-- 		<div class="modal fade" id="layanan" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						
					</div>
					<div class="modal-body">
						
					</div>
					<div class="modal-footer">
						
					</div>
				</div>
			</div>
		</div> -->

		<form @submit.prevent="createLayanan" type="post" v-show="!editState">
			<div class="form-group" :class="{'has-error': errors.aktivitas}">
				<input type="text" v-model="layanan.aktivitas" placeholder="Aktivitas" class="form-control">
				<span class="help-block" v-for="error in errors.aktivitas" :key="error.key">{{error}}</span>
			</div>
			<div class="form-group" :class="{'has-error': errors.tahun}">
				<input type="text" v-model="layanan.tahun" placeholder="Tahun" class="form-control">
				<span class="help-block" v-for="error in errors.tahun" :key="error.key">{{error}}</span>
			</div>
			<div class="form-group pull-right">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</form>

		<form @submit.prevent="updateLayanan" type="post" v-show="editState" >
			<div class="form-group" :class="{'has-error': errorsEdit.aktivitas}">
				<input type="text" v-model="layananEdit.aktivitas" placeholder="Aktivitas" class="form-control">
				<span class="help-block" v-for="error in errorsEdit.aktivitas" :key="error.key">{{error}}</span>
			</div>
			<div class="form-group" :class="{'has-error': errorsEdit.tahun}">
				<input type="text" v-model="layananEdit.tahun" placeholder="Tahun" class="form-control">
				<span class="help-block" v-for="error in errorsEdit.tahun" :key="error.key">{{error}}</span>
			</div>
			<div class="form-group pull-right">
				<button type="submit" class="btn btn-info">Update</button>
				<button type="button" class="btn btn-danger" @click="batalEdit">Batal</button>
			</div>
		</form>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Aktivitas</th>
					<th>Tahun</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="layanan in layanans" :key="layanan.key">
					<td> {{layanan.aktivitas}} </td>
					<td> {{layanan.tahun}} </td>
					<td>
						<button type="button" class="btn btn-info" @click="getEdit(layanan)">Edit</button>
						<button type="button" class="btn btn-danger" @click="hapusLayanan(layanan)">Delete</button>
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
			layanan: {
				aktivitas: '',
				tahun: ''
			},
			layananEdit: {
				aktivitas: '',
				tahun: ''
			},
			layanans: [],
			editState: false,
			errors: [],
			errorsEdit: [],
			links: [],
			meta: []
		};
	},

	created(){
		this.fetchLayanan();
	},

	methods: {
		fetchLayanan(){
			axios.get('/layanan').then(respo => {
				this.layanans = respo.data.data;
				this.links = respo.data.links;
				this.meta = respo.data.meta;
			});
		},

		createLayanan() {
			axios.post('/layanan', this.layanan).then(respon => {
				this.layanans.push(respon.data.layanan);
				this.layanan = {
					aktivitas: '',
					tahun: ''
				};
				this.errors = [];
			}).catch(respon => {
				this.errors = respon.response.data.errors;
			});
		},

		getEdit(edit) {
			this.editState = true;
			this.layananEdit = edit;
		},

		batalEdit() {
			this.editState = false;
			this.layananEdit = {
				aktivitas: '',
				tahun: ''
			}
		},

		updateLayanan(){
			axios.patch('/layanan/'+this.layananEdit.id, this.layananEdit).then(respon => {
				this.editState = false;
				this.layananiEdit = {
					aktivitas: '',
					tahun: '',
					
				};
				this.errorsEdit = [];
			}).catch(respon => {
				this.errorsEdit = respon.response.data.errors;
			});
		},

		hapusLayanan(layanan) {
			axios.delete('/layanan/'+layanan.id).then(respo => {
				let index = this.layanans.indexOf(layanan);
				this.layanans.splice(index,1);
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

		// setFocus: function() {
  //     // Note, you need to add a ref="search" attribute to your input.
  //     	this.$refs.search.focus();
		// }

	}
}
</script>