<template>
    <div class="d-flex flex-column gc-8">
        <div class="d-flex my-10 px-10 gc-8">
            <!-- Accept .xlsx ONLY -->
            <v-file-input
                v-model="file"
                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                density="compact"
                hint="Kindly Upload Excel Workbook (.xlsx) File Only."
                max-width="40%"
                chips
                clearable
                persistent-hint
            />
            <v-btn @click="upload" color="success" text="Upload" />
        </div>
        <v-divider />
        <div class="px-10 pt-6">
            <div class="d-flex align-center justify-space-between mb-10">
                <h4>Product Master List</h4>
                <v-text-field
                    density="compact"
                    placeholder="Search"
                    max-width="20%"
                    hide-details
                />
            </div>
            <v-data-table-server
                v-model:items-per-page="itemsPerPage"
                v-model:page="page"
                :sort-by.sync="sortBy"
                :headers="headers"
                :items="modified?.data"
                :items-length="data?.total || 0"
                :page="page"
                @update:itemsPerPage="fetchData"
                @update:page="fetchData"
                @update:sortBy="sortData"
                hover
                show-current-page
            />
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref, watch } from 'vue'
import Swal from 'sweetalert2'

const data = ref(null)
const modified = ref([])
const file = ref(null)
const itemsPerPage = ref(15)
const page = ref(1)
const sortBy = ref([])

const headers = [
    { title: 'No.', value: 'number', sortable: true },
    { title: 'Product ID', value: 'product_id', sortable: true },
    { title: 'Types', value: 'type.name', sortable: true },
    { title: 'Brand', value: 'brand.name', sortable: true },
    { title: 'Model', value: 'model.name', sortable: true },
    { title: 'Capacity', value: 'model.capacity.name', sortable: true },
    { title: 'Quantity', value: 'quantity', sortable: true }
]

watch(file, (uploadedFile) => {
    if(uploadedFile && uploadedFile.type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
        popup(`<p>Invalid file type, please upload again.</p> <small>[Only Excel Workbook (.xlsx) file is accepted.]</small>`, true, () => {
            file.value = null
        })
    }
}, {
    immediate: true
})

watch(sortBy, () => {
    if(sortBy.value.length === 0) {
        modified.value = JSON.parse(JSON.stringify(data.value))
        return
    }

    modified.value.data.sort((a, b) => {
        for(let { key, order } of sortBy.value) {
            let firstValue = a[key]
            let secondValue = b[key]

            if(key.includes('.')) {
                firstValue = key.split('.').reduce((item, child) => item && item[child], a)
                secondValue = key.split('.').reduce((item, child) => item && item[child], b)
            }

            if(firstValue > secondValue) {
                return order === 'desc' ? -1 : 1
            }

            if(firstValue < secondValue) {
                return order === 'desc' ? 1 : -1
            }

            return 0
        }
    })
});

onMounted(() => {
    fetchData()
})

const fetchData = () => {
    axios.get(`/api/products?itemsPerPage=${itemsPerPage.value}&page=${page.value}`).then((res) => {
        if(res.status) {
            data.value = res.data.data
            modified.value = JSON.parse(JSON.stringify(data.value))
        }
    }).catch((err) => {
        popup(err.response.data.message, false)
    })
}

const upload = () => {
    let formData = new FormData();
    formData.append('file', file.value);

    axios.post('/api/products/upload', formData).then((res) => {
        popup(res.data.data, false, fetchData, 'Yay!', 'success')
    }).catch((err) => {
        popup(err.response.data.message, false)
    })
}

const sortData = (sortField) => {
    sortBy.value = sortField
}

const popup = (message, isHTML, callback = null, title = 'Oops..', icon = 'error') => {
    Swal.fire({
        icon,
        title,
        text: isHTML ? '' : message,
        html: isHTML ? message : ''
    }).then((resp) => {
        if(resp.isConfirmed) {
            callback()
        }
    })
}
</script>

<style lang="scss">
    .container {
        padding: 1.5rem 3rem;
    }
</style>
