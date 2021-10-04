<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-4 mg-b-20">
                <div class="card">
                    <div class="card-header">Add New User</div>

                    <div class="card-body">
                        <ul v-if="errors.length">
                            <li class="text-danger tx-11" v-for="error in errors" :key="error">
                                {{ error }}
                            </li>
                        </ul>
                        <form @submit.prevent="submit" class="needs-validation">
                            <div class="form-group">
                                <input type="text" v-model="form.first_name" name="first"
                                    class="form-control rounded-5" placeholder="First Name"
                                    >
                            </div>
                            <div class="form-group">
                                <input type="text" v-model="form.last_name" name="last"
                                    class="form-control rounded-5" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <input type="text" v-model="form.username" name="username"
                                    class="form-control rounded-5" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="password" v-model="form.password" name="password"
                                    class="form-control rounded-5" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <input type="password" v-model="form.confirm" name="confirm"
                                    class="form-control rounded-5" placeholder="Confirm Password">
                            </div>
                            <div class="form-group">
                                <select name="role" class="form-control rounded-5" v-model="form.role">
                                    <option value="Student">Student</option>
                                    <option value="Instructor">Instructor</option>
                                    <option value="Dean">Dean</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info btn-sm rounded-5"
                                :class="editStatus ? 'btn-warning' : 'btn-info' ">
                                {{ editStatus ? 'Update' : 'Create' }}
                            </button>
                            <button @click="reset" type="button" class="btn btn-light btn-sm rounded-5 mg-l-10">
                                Reset
                            </button>
                            <span class="mg-b-0 animated" :class="createStatus ? 'zoomIn' : 'zoomOut' ">{{ message }}</span>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mg-b-20">
                <div class="card mg-b-20">
                    <div class="card-header bg-transparent d-flex justify-content-between">
                        <input type="text" v-model="search" class="form-control rounded-5 wd-200" placeholder="Search">
                        <button type="button" @click="quoteOfTheDay" class="btn btn-sm btn-info wd-150 rounded-5">
                            {{ quoteStatus ? 'Fetching...' : 'Get Quote' }}
                        </button>
                    </div>


                    <div class="card-body">
                        <div class="text-center" :class="tableStatus ? 'd-none' : 'd-block' ">
                            <div class="spinner-border"></div>
                        </div>
                        <div class="table-responsive-md" :class="tableStatus ? 'd-block' : 'd-none' ">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="pos-relative">
                                            Fist Name
                                            <div class="pos-absolute" style="top:4px; right: 5px;">
                                                <span class="tx-10" @click="arrowUp('first_name')"><i class="fas fa-arrow-up"></i></span>
                                                <span class="tx-10" @click="arrowDown('first_name')"><i class="fas fa-arrow-down"></i></span>
                                            </div>
                                        </th>
                                        <th class="pos-relative">
                                            Last Name
                                            <div class="pos-absolute" style="top:4px; right: 5px;">
                                                <span class="tx-10" @click="arrowUp('last_name')"><i class="fas fa-arrow-up"></i></span>
                                                <span class="tx-10" @click="arrowDown('last_name')"><i class="fas fa-arrow-down"></i></span>
                                            </div>
                                        </th>
                                        <th class="pos-relative">
                                            Username
                                            <div class="pos-absolute" style="top:4px; right: 5px;">
                                                <span class="tx-10" @click="arrowUp('username')"><i class="fas fa-arrow-up"></i></span>
                                                <span class="tx-10" @click="arrowDown('username')"><i class="fas fa-arrow-down"></i></span>
                                            </div>
                                        </th>
                                        <th class="pos-relative">
                                            Role
                                            <div class="pos-absolute" style="top:4px; right: 5px;">
                                                <span class="tx-10" @click="arrowUp('role')"><i class="fas fa-arrow-up"></i></span>
                                                <span class="tx-10" @click="arrowDown('role')"><i class="fas fa-arrow-down"></i></span>
                                            </div>
                                        </th>
                                        <th class="text-center">Tools</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="tables.length == 0">
                                        <td colspan="5">No records found</td>
                                    </tr>
                                    <tr v-for="(table, index) in tables" :key="index">
                                        <td>{{ table.first_name }}</td>
                                        <td>{{ table.last_name }}</td>
                                        <td>{{ table.username }}</td>
                                        <td>
                                            <span :class="table.role === 'Student' ? 'text-info' : 'text-danger' ">
                                                {{ table.role }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="" @click.prevent="getUserById(table.id)" class="badge badge-pill badge-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="" @click.prevent="getDestroy(table.id)" class="badge badge-pill badge-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card card-body pd-lg-25 mg-b-10" v-if="quote && author">
                    <blockquote class="blockquote bd-l bd-5 pd-l-20 mg-b-0">
                        <p class="mg-b-5 tx-inverse tx-15 text-center">{{ quote }}</p>
                        <footer class="blockquote-footer tx-13">{{ author }}</footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                form: {},
                errors: [],
                tables: [],
                backUp: [],
                tableStatus: false,
                createStatus: false,
                editStatus: false,
                quoteStatus: false,
                message: '',
                search: '',
                quote: '',
                author: '',
                id: 0,
            }
        },

        watch: {

            search() {
                this.searchFilter();
            }

        },

        methods: {

            submit() {

                this.errors = [];

                if (!this.form.first_name) {
                    this.errors.push('First Name is required.');
                }
                if (!this.form.last_name) {
                    this.errors.push('Last Name is required.');
                }
                if (!this.form.username) {
                    this.errors.push('Username is required.');
                }
                if (!this.form.password && this.editStatus == false) {
                    this.errors.push('Password is required.');
                }
                if (!this.form.confirm && this.editStatus == false) {
                    this.errors.push('Confirm Password is required.');
                }
                if(!this.form.role) {
                    this.errors.push('Role is required.')
                }

                if (!this.errors.length) {

                    if (this.editStatus) {
                        this.axios.post(`/api/user/${this.id}/update`, this.form)
                        .then((result) => {
                            if(result.data.error) {
                                this.errors.push(result.data.message);
                            }else {
                                var id = this.id;
                                var map = this.tables.map(({id}) => id);
                                var index = map.indexOf(id);
                                this.tables.splice(index, 1);
                                this.tables.unshift(result.data.data);
                                this.form = {};
                                this.editStatus = false;
                                this.id = 0;
                                this.createStatus = true;
                                this.message = result.data.message;

                                setTimeout(() => {
                                    this.createStatus = false;
                                    this.message = '';
                                }, 2000);
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                    }else {
                        this.axios.post('/api/user', this.form)
                        .then((result) => {
                            if (result.data.error) {
                                this.errors.push(result.data.message);
                            }else {
                                this.form = {};
                                this.tables.unshift(result.data.data);
                                this.createStatus = true;
                                this.message = result.data.message;
                                this.search = '';

                                setTimeout(() => {
                                    this.createStatus = false;
                                    this.message = '';
                                }, 2000);
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                    }

                }

            },

            getUserById(id) {
                this.axios.get(`/api/user/${id}`)
                .then((result) => {
                    this.form = result.data;
                    this.editStatus = true;
                    this.id = result.data.id;
                })
                .catch((error) => {
                    console.log(' The servers ' + error);
                })
            },

            getDestroy(id) {

                this.axios.delete(`/api/user/${id}/delete`)
                .then((result) => {
                    var map = this.tables.map(({id}) => id);
                    var index = map.indexOf(id);
                    this.tables.splice(index, 1);
                    if (this.id == id) {
                        this.reset();
                    }
                    alert(result.data);
                })
                .catch((error) => {
                    console.log(error);
                });

            },

            getData() {

                this.axios.get('/api/users')
                .then((result) => {
                    this.tables = result.data;
                    this.backUp = result.data;
                    this.tableStatus = true;
                }).catch((err) => {
                    console.log('Server down ' + err);
                });

            },

            reset() {
                this.id = 0;
                this.editStatus = false;
                this.form = {};
            },

            arrowDown(target) {
                let sort = -1;
                //this.tables.sort(this.dynamicSort(target, sort));
                this.dynamicSort(target, sort);
            },

            arrowUp(target) {
                let sort = 1;
                //this.tables.sort(this.dynamicSort(target, sort));
                this.dynamicSort(target, sort);
            },

            dynamicSort(key, sort) {

                // return function (a, b) {
                //     if (sort == 1) {
                //         return a[key].localeCompare(b[key]);
                //     }else {
                //         return b[key].localeCompare(a[key]);
                //     }
                // }

                let local = this.search ? this.search : '*';

                this.axios.get(`/api/search/${local}/${key}/${sort}`)
                .then((result) => {
                    this.tables = result.data;
                })
                .catch((error) => {
                    console.log(error);
                });

            },

            async searchFilter() {

                if (!this.search) {
                    this.tables = this.backUp;
                }

                // this.tables = this.backUp.filter(row => {
                //     const first = row.first_name.toString().toLowerCase();
                //     const last = row.last_name.toString().toLowerCase();
                //     const username = row.username.toString().toLowerCase();
                //     const role = row.role.toString().toLowerCase();

                //     return first.includes(this.search.toLowerCase()) ||
                //             last.includes(this.search.toLowerCase()) ||
                //             username.includes(this.search.toLowerCase()) ||
                //             role.includes(this.search.toLowerCase());
                // });

                if (this.search) {
                    this.axios.get(`/api/search/${this.search}/id/0`)
                    .then((result) => {
                        this.tables = result.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                }

            },

            quoteOfTheDay() {
                this.quoteStatus = true;
                let headers = {
                    'Content-Type' : 'application/json;charset=utf-8;',
                    'Access-Control-Allow-Origin' : '*'
                };
                this.axios.get('https://api.quotable.io/random',
                {headers : headers})
                .then((result) => {
                    this.author = result.data.author;
                    this.quote = result.data.content;
                    this.quoteStatus = false;
                })
                .catch((error) => {
                    console.log(error);
                })
            }

        },

        mounted() {

            this.getData();

        }
    }
</script>
