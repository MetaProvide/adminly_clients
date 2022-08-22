<template>
	<div class="clients-table">
		<div class="top-bar">
			<input
				v-model="searchName"
				placeholder="Search Client"
				@input="search"
			/>
			<ClientCreation @update-clients="updateClients" />
		</div>
		<Table
			:key="currentPage"
			:clients="tableContent"
			:is-empty="isTableEmpty"
			@update-clients="updateClients"
		/>
		<div v-if="tableContent.length" class="table-footer">
			<div class="left">
				<div class="page-info">
					<span>
						{{ paginationInfo }}
					</span>
				</div>
			</div>
			<div class="center">
				<div class="table-nav">
					<button
						class="svg first-page-button"
						@click="getFirstPage()"
					></button>
					<button
						class="svg previous-button"
						@click="previousPage()"
					></button>
					<input
						v-model="goToPage"
						:min="1"
						:max="totalPages"
						type="number"
						@input="getPage(goToPage)"
					/>
					<button
						class="svg next-button"
						@click="nextPage()"
					></button>
					<button
						class="svg last-page-button"
						@click="getLastPage()"
					></button>
				</div>
			</div>
			<div class="right">
				<div class="page-selector">
					<select v-model="clientsPerPage" @change="updateTable()">
						<option value="10" selected>10</option>
						<option value="20">20</option>
						<option value="30">30</option>
					</select>
					<p>clients per page</p>
				</div>
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

			const client = this.totalClients === 1 ? " client" : " clients";

			return this.currentPage === this.totalPages
				? firstClientOfPage +
						" - " +
						this.totalClients +
						" of " +
						this.totalClients +
						client
				: firstClientOfPage +
						" - " +
						this.currentPage * this.clientsPerPage +
						" of " +
						this.totalClients +
						client;
		},
		tableData() {
			return this.searchName ? this.clientSearchList : this.clients;
		},
	},
	watch: {
		clients() {
			this.updateTable();
			this.getPage(this.currentPage);
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

			this.goToPage = this.currentPage;
		},
		nextPage() {
			if (this.currentPage < this.totalPages) {
				this.currentPage += 1;
				this.goToPage = this.currentPage;
				this.tableContent = this.tableData.slice(
					(this.currentPage - 1) * this.clientsPerPage,
					Math.min(
						this.currentPage * this.clientsPerPage,
						this.totalClients
					)
				);
			}
		},
		previousPage() {
			if (this.currentPage > 1) {
				this.currentPage -= 1;
				this.goToPage = this.currentPage;
				this.tableContent = this.tableData.slice(
					(this.currentPage - 1) * this.clientsPerPage,
					this.currentPage * this.clientsPerPage
				);
			}
		},
		getFirstPage() {
			this.getPage(1);
		},
		getLastPage() {
			this.getPage(this.totalPages);
		},
		getPage(pageNum) {
			if (pageNum <= this.totalPages && pageNum && pageNum > 0) {
				this.currentPage = pageNum;
				this.goToPage = this.currentPage;
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
			this.currentPage = 1;
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
	align-items: center;
	justify-content: center;
}

.page-selector {
	display: flex;
	align-items: center;
	justify-content: flex-end;
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

input,
select {
	border: 1px solid var(--color-border-dark);
}

.table-footer input {
	width: 34px;
	text-align: center;
	border-radius: 3px;
}

.top-bar {
	display: flex;
	justify-content: space-between;
}

.top-bar input {
	width: 247px;
	border-radius: 6px;
	background-color: white;
	padding: 12px 20px 12px 40px;
	background-position: 10px;
	background-repeat: no-repeat;
	background-image: url("../../img/search.svg");
}

.page-info {
	display: flex;
	justify-content: flex-start;
	padding: 0.7rem;
	font-weight: 700;
}

.table-footer {
	box-shadow: 0px 0px 10px var(--adminly-grey);
	border-radius: 15px;
	display: inline-flex;
	background-color: white;
	padding: 0.25rem 1.25rem;
}

.active {
	background-color: #eef4fc !important;
}

select {
	font-size: unset;
	padding-inline: 0.75rem 2.5rem;
	background: url("../../img/select-arrow.svg") no-repeat right 6px center;
	margin-right: 1rem;
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
}

.previous-button {
	background-image: url("../../img/left-arrow.svg");
}

.first-page-button {
	background-image: url("../../img/first-page-arrow.svg");
}

.last-page-button {
	background-image: url("../../img/last-page-arrow.svg");
}

.table-footer input::-webkit-outer-spin-button,
.table-footer input::-webkit-inner-spin-button {
	-webkit-appearance: none;
	margin: 0;
}

.table-footer input[type="number"] {
	-moz-appearance: textfield;
}

.left {
	flex: 1 0;
	white-space: nowrap;
	min-width: 0;
}

.center {
	display: inline-flex;
}

.right {
	display: flex;
	align-items: center;
	flex: 1;
	justify-content: flex-end;
}
</style>
