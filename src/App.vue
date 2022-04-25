<template>
	<main>
		<div class="flex">
			<CreateClient />
			<input v-model="searchName" v-on:input="search" />
		</div>
		<ClientsTable :key="pageNumber" :clients="tableContent" />
		<div class="tableNav">
			<button @click="previousPage()">Prev</button>
			<button @click="nextPage()">Next</button>
		</div>
	</main>
</template>

<script>
import CreateClient from "./components/CreateClient";
import ClientsTable from "./components/ClientsTable";

export default {
	name: "App",
	components: {
		CreateClient,
		ClientsTable,
	},
	props: {
		clients: {
			type: Array,
			default() {
				return [];
			},
		},
	},
	data() {
		return {
			tableContent: [],
			pageNumber: 0,
			totalPages: 0,
			clientsPerPage: 10,
			totalClients: 0,
			searchName: "",
		};
	},
	async mounted() {
		this.tableContent = this.clients.slice(0, this.clientsPerPage);
		this.totalClients = this.clients.length;
		this.totalPages = Math.ceil(this.totalClients / this.clientsPerPage);
	},
	methods: {
		nextPage() {
			if (this.pageNumber === this.totalPages - 2) {
				this.pageNumber += 1;
				this.tableContent = this.clients.slice(
					this.pageNumber * this.clientsPerPage,
					this.totalClients
				);
			} else if (this.pageNumber < this.totalPages - 1) {
				this.pageNumber += 1;
				this.tableContent = this.clients.slice(
					this.pageNumber * this.clientsPerPage,
					(this.pageNumber + 1) * this.clientsPerPage
				);
			}
		},
		previousPage() {
			if (this.pageNumber > 0) {
				this.pageNumber -= 1;
				this.tableContent = this.clients.slice(
					this.pageNumber * this.clientsPerPage,
					(this.pageNumber + 1) * this.clientsPerPage
				);
			}
		},
		search() {
			this.tableContent = this.clients.filter((p) => {
				// return true if the product should be visible

				// in this example we just check if the search string
				// is a substring of the product name (case insensitive)
				return (
					p.name
						.toLowerCase()
						.indexOf(this.searchName.toLowerCase()) != -1
				);
			});
		},
	},
};
</script>

<style scoped>
main {
	width: 100%;
	min-height: 100vh;
	padding: 20px;
	display: flex;
	justify-content: center;
	gap: 20px;
	flex-direction: column;
}

.clients {
	font-weight: bold;
}

.tableNav {
	display: flex;
	justify-content: right;
}

.flex {
	display: flex;
}
</style>
