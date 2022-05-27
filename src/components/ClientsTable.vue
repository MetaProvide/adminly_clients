<template>
	<div class="clientsTable">
		<div class="flex">
			<ClientCreation @update-clients="updateClients" />
			<input
				v-model="searchName"
				placeholder="Search Client"
				@input="search"
			/>
		</div>
		<Table
			:key="currentPage"
			:clients="tableContent"
			@update-clients="updateClients"
		/>
		<div v-if="tableContent.length" class="tableFooter">
			<div class="pageInfo">
				<span>
					{{ paginationInfo }}
				</span>
				<select v-model="clientsPerPage" @change="updateTable()">
					<option value="10" selected>10/page</option>
					<option value="20">20/page</option>
					<option value="30">30/page</option>
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
	</div>
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
			clientSearchList: [],
		};
	},
	computed: {
		paginationInfo() {
			return this.currentPage === this.totalPages
				? (this.currentPage - 1) * this.clientsPerPage +
						" - " +
						this.totalClients +
						" of " +
						this.totalClients +
						" clients"
				: (this.currentPage - 1) * this.clientsPerPage +
						" - " +
						this.currentPage * this.clientsPerPage +
						" of " +
						this.totalClients +
						" clients";
		},
	},
	watch: {
		clients() {
			this.updateTable();
		},
	},
	methods: {
		updateTable() {
			this.tableContent = this.searchName
				? this.clientSearchList.slice(0, this.clientsPerPage)
				: this.clients.slice(0, this.clientsPerPage);

			this.totalClients = this.searchName
				? this.clientSearchList.length
				: this.clients.length;

			this.totalPages = Math.ceil(
				this.totalClients / this.clientsPerPage
			);
			if (this.currentPage > this.totalPages) this.currentPage = 1;
		},
		nextPage() {
			this.currentPage += 1;
			this.tableContent = this.searchName
				? this.clientSearchList.slice(
						(this.currentPage - 1) * this.clientsPerPage,
						Math.min(
							this.currentPage * this.clientsPerPage,
							this.totalClients
						)
				  )
				: this.clients.slice(
						(this.currentPage - 1) * this.clientsPerPage,
						Math.min(
							this.currentPage * this.clientsPerPage,
							this.totalClients
						)
				  );
		},
		previousPage() {
			this.currentPage -= 1;
			this.tableContent = this.searchName
				? this.clientSearchList.slice(
						(this.currentPage - 1) * this.clientsPerPage,
						this.currentPage * this.clientsPerPage
				  )
				: this.clients.slice(
						(this.currentPage - 1) * this.clientsPerPage,
						this.currentPage * this.clientsPerPage
				  );
		},
		getPage(pageNum) {
			if (pageNum <= this.totalPages && pageNum) {
				this.currentPage = pageNum;
				this.tableContent = this.searchName
					? this.clientSearchList.slice(
							(this.currentPage - 1) * this.clientsPerPage,
							Math.min(
								this.currentPage * this.clientsPerPage,
								this.totalClients
							)
					  )
					: this.clients.slice(
							(this.currentPage - 1) * this.clientsPerPage,
							Math.min(
								this.currentPage * this.clientsPerPage,
								this.totalClients
							)
					  );
			}
		},
		search() {
			this.clientSearchList = this.clients.filter((p) => {
				return (
					p.name
						.toLowerCase()
						.indexOf(this.searchName.toLowerCase()) !== -1
				);
			});
			this.updateTable();
		},
		updateClients() {
			this.$emit("update-clients", true);
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
	font-weight: normal;
	border-radius: 5px;
}

.tableNav p {
	margin: 10px;
}

input {
	box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);
	border-radius: 8px;
}

.tableFooter input {
	max-width: 60px;
}

.flex {
	display: flex;
}

.pageInfo {
	justify-content: left;
	padding: 10px;
}

.tableFooter {
	box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);
	border-radius: 15px;
	display: flex;
	justify-content: space-between;
	padding: 0 20px;
}

.active {
	background-color: #eef4fc !important;
}

select {
	border: none;
	font-size: unset;
}

.clientsTable {
	width: 80%;
	margin: auto;
	min-height: 100vh;
	padding: 20px;
	display: flex;
	gap: 20px;
	flex-direction: column;
	align-content: center;
}
</style>
