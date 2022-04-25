<template>
	<main>
		<div class="flex">
			<CreateClient />
			<input
				v-model="searchName"
				placeholder="Search Client"
				@input="search"
			/>
		</div>
		<ClientsTable :key="pageNumber" :clients="tableContent" />
		<div class="tableFooter">
			<div v-if="pageNumber === totalPages - 1" class="pageInfo">
				{{ pageNumber * clientsPerPage }} - {{ totalClients }} of
				{{ totalClients }}
			</div>
			<div v-else class="pageInfo">
				{{ pageNumber * clientsPerPage }} -
				{{ (pageNumber + 1) * clientsPerPage }} of
				{{ totalClients }} clients
			</div>
			<div class="tableNav">
				<button @click="previousPage()">
					<svg
						width="10"
						height="15"
						viewBox="0 0 10 15"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
					>
						<path
							fill-rule="evenodd"
							clip-rule="evenodd"
							d="M3.02714 7.61528L9.14502 1.4974L8.10172 0.454102L0.940539 7.61528L8.10172 14.7765L9.14502 13.7332L3.02714 7.61528Z"
							fill="#868C92"
						/>
					</svg>
				</button>
				<button @click="nextPage()">
					<svg
						width="9"
						height="15"
						viewBox="0 0 9 15"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
					>
						<path
							fill-rule="evenodd"
							clip-rule="evenodd"
							d="M6.24508 7.61421L0.127197 13.7321L1.1705 14.7754L8.33168 7.61421L1.1705 0.453031L0.127197 1.49633L6.24508 7.61421Z"
							fill="#8E8E8E"
						/>
					</svg>
				</button>
			</div>
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
			clientsPerPage: 8,
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
				return (
					p.name
						.toLowerCase()
						.indexOf(this.searchName.toLowerCase()) !== -1
				);
			});
		},
	},
};
</script>

<style scoped>
main {
	width: 80%;
	margin: auto;
	min-height: 100vh;
	padding: 20px;
	display: flex;
	gap: 20px;
	flex-direction: column;
	align-content: center;
}

.clients {
	font-weight: bold;
}

.tableNav {
	display: flex;
	justify-content: right;
}

.tableNav button {
	border: none;
	background-color: white;
}

input {
	box-shadow: 0px 0px 10.8184px rgba(0, 0, 0, 0.15);
	border-radius: 8px;
}

.flex {
	display: flex;
}

.pageInfo {
	justify-content: left;
	padding: 10px;
}

.tableFooter {
	box-shadow: 0px 0px 9.68155px rgba(0, 0, 0, 0.15);
	border-radius: 15px;
	display: flex;
	justify-content: space-between;
}
</style>
