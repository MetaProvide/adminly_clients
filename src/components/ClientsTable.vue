<template>
	<main>
		<div class="flex">
			<ClientCreation />
			<input
				v-model="searchName"
				placeholder="Search Client"
				@input="search"
			/>
		</div>
		<Table :key="currentPage" :clients="tableContent" />
		<div class="tableFooter">
			<div class="pageInfo">
				<span v-if="currentPage === totalPages">
					{{ (currentPage - 1) * clientsPerPage }} -
					{{ totalClients }} of
					{{ totalClients }}
				</span>
				<span v-else>
					{{ (currentPage - 1) * clientsPerPage }} -
					{{ currentPage * clientsPerPage }} of
					{{ totalClients }} clients
				</span>
				<select v-model="clientsPerPage" @change="updateTable()">
					<option value="10" selected>10</option>
					<option value="20">20</option>
					<option value="30">30</option>
				</select>
			</div>
			<div class="tableNav">
				<button v-if="currentPage > 1" @click="previousPage()">
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
				<div v-for="page in totalPages" :key="page">
					<button
						v-if="page === currentPage"
						class="active"
						@click="getPage(page)"
					>
						{{ page }}
					</button>
					<button v-else @click="getPage(page)">{{ page }}</button>
				</div>
				<button v-if="currentPage < totalPages" @click="nextPage()">
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
				<p>Go to</p>
				<input
					v-model="goToPage"
					type="number"
					@input="getPage(goToPage)"
				/>
			</div>
		</div>
	</main>
</template>

<script>
import ClientCreation from "./ClientCreation";
import Table from "./Table";

export default {
	components: {
		ClientCreation,
		Table,
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
			currentPage: 1,
			totalPages: 0,
			clientsPerPage: 10,
			totalClients: 0,
			searchName: "",
			goToPage: "",
		};
	},
	async mounted() {
		this.updateTable();
	},
	methods: {
		updateTable() {
			this.tableContent = this.clients.slice(0, this.clientsPerPage);
			this.totalClients = this.clients.length;
			this.totalPages = Math.ceil(
				this.totalClients / this.clientsPerPage
			);
		},
		nextPage() {
			this.currentPage += 1;
			this.tableContent = this.clients.slice(
				(this.currentPage - 1) * this.clientsPerPage,
				Math.min(
					this.currentPage * this.clientsPerPage,
					this.totalClients
				)
			);
		},
		previousPage() {
			this.currentPage -= 1;
			this.tableContent = this.clients.slice(
				(this.currentPage - 1) * this.clientsPerPage,
				this.currentPage * this.clientsPerPage
			);
		},
		getPage(pageNum) {
			this.currentPage = pageNum;
			this.tableContent = this.clients.slice(
				(this.currentPage - 1) * this.clientsPerPage,
				Math.min(
					this.currentPage * this.clientsPerPage,
					this.totalClients
				)
			);
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
.clients {
	font-weight: bold;
}

.tableNav {
	display: flex;
	justify-content: right;
	align-items: center;
}

.tableNav button {
	border: none;
	background-color: white;
}

.tableNav p {
	margin: 10px;
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

.active {
	background-color: #eef4fc !important;
}

select {
	border: none;
}
</style>
