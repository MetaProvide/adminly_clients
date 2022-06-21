<template>
	<div class="clients-table">
		<div class="top-bar">
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
			:is-empty="isTableEmpty"
			@update-clients="updateClients"
		/>
		<div v-if="tableContent.length" class="table-footer">
			<div class="page-info">
				<span>
					{{ paginationInfo }}
				</span>
				<select v-model="clientsPerPage" @change="updateTable()">
					<option value="10" selected>10/page</option>
					<option value="20">20/page</option>
					<option value="30">30/page</option>
				</select>
			</div>
			<div class="table-nav">
				<button
					v-if="currentPage > 1"
					class="previous-button"
					@click="previousPage()"
				></button>
				<div v-for="page in totalPages" :key="page">
					<button
						v-if="page == currentPage"
						class="active"
						@click="getPage(page)"
					>
						{{ page }}
					</button>
					<button v-else @click="getPage(page)">
						{{ page }}
					</button>
				</div>
				<button
					v-if="currentPage < totalPages"
					class="next-button"
					@click="nextPage()"
				></button>
				<p>Go to</p>
				<input
					v-model="goToPage"
					:min="1"
					:max="totalPages"
					type="number"
					placeholder="Page"
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
		isEmpty: {
			type: Boolean,
			default() {
				return false;
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
			isTableEmpty: false,
		};
	},
	computed: {
		paginationInfo() {
			const firstClientOfPage =
				this.currentPage - 1 === 0
					? 1
					: (this.currentPage - 1) * this.clientsPerPage;

			return this.currentPage === this.totalPages
				? firstClientOfPage +
						" - " +
						this.totalClients +
						" of " +
						this.totalClients +
						" clients"
				: firstClientOfPage +
						" - " +
						this.currentPage * this.clientsPerPage +
						" of " +
						this.totalClients +
						" clients";
		},
		tableData() {
			return this.searchName ? this.clientSearchList : this.clients;
		},
	},
	watch: {
		clients() {
			this.updateTable();
			if (this.searchName) this.search();
		},
	},
	methods: {
		updateTable() {
			this.tableContent = this.tableData.slice(0, this.clientsPerPage);

			this.isTableEmpty = this.tableContent.length === 0;

			this.totalClients = this.tableData.length;

			this.totalPages = Math.ceil(
				this.totalClients / this.clientsPerPage
			);
			if (this.currentPage > this.totalPages) this.currentPage = 1;
		},
		nextPage() {
			this.currentPage += 1;
			this.tableContent = this.tableData.slice(
				(this.currentPage - 1) * this.clientsPerPage,
				Math.min(
					this.currentPage * this.clientsPerPage,
					this.totalClients
				)
			);
		},
		previousPage() {
			this.currentPage -= 1;
			this.tableContent = this.tableData.slice(
				(this.currentPage - 1) * this.clientsPerPage,
				this.currentPage * this.clientsPerPage
			);
		},
		getPage(pageNum) {
			if (pageNum <= this.totalPages && pageNum && pageNum > 0) {
				this.currentPage = pageNum;
				this.tableContent = this.tableData.slice(
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

.table-nav {
	display: flex;
	justify-content: right;
	align-items: center;
	color: var(--neutral-600);
}

.table-nav button {
	border: none;
	background-color: white;
	font-weight: normal;
	border-radius: 5px;
}

.table-nav p {
	margin: 0.7rem;
}

input {
	box-shadow: 0px 0px 10px var(--adminly-grey);
	border-radius: 8px;
}

.table-footer input {
	max-width: 60px;
}

.top-bar {
	display: flex;
}

.top-bar input {
	height: 43px !important;
	width: 170px;
	background-color: white;
	padding: 12px 20px 12px 40px;
	background-position: 10px 10px;
	background-repeat: no-repeat;
	background-image: url("../../img/search.svg");
}

.page-info {
	justify-content: left;
	padding: 0.7rem;
	color: var(--neutral-500);
}

.table-footer {
	box-shadow: 0px 0px 10px var(--adminly-grey);
	border-radius: 15px;
	display: flex;
	justify-content: space-between;
	background-color: white;
	padding: 0 1.25rem;
}

.active {
	background-color: #eef4fc !important;
}

select {
	border: none;
	font-size: unset;
	color: var(--neutral-500);
}

.clients-table {
	min-width: 80%;
	margin: auto;
	padding: 20px;
	display: flex;
	gap: 20px;
	flex-direction: column;
	align-content: center;
}

.next-button {
	background-image: url("../../img/right-arrow.svg");
	background-position: center;
	background-repeat: no-repeat;
}

.previous-button {
	background-image: url("../../img/left-arrow.svg");
	background-position: center;
	background-repeat: no-repeat;
}
</style>
