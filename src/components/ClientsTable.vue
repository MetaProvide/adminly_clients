<template>
	<div class="clients-table">
		<div class="top-bar">
			<input
				v-model="searchName"
				placeholder="Search Client"
				@input="search"
			/>
			<button class="create-btn" @click="toggleNewClientModal()">
				<div class="svg add-client"></div>
				<p>Add Client</p>
			</button>
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
			<div v-if="!searchName" class="center">
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
			<div v-if="!searchName" class="right">
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
		<router-view @update-clients="updateClients" />
	</div>
</template>

<script>
import { ClientsUtil } from "../utils";
import Table from "./Table";
import axios from "@nextcloud/axios";

export default {
	components: {
		Table,
	},
	data() {
		return {
			tableContent: [],
			currentPage: 1,
			totalPages: 0,
			clientsPerPage: 10,
			totalClients: 0,
			searchName: "",
			searchResults: 0,
			goToPage: 1,
			clientSearchList: [],
			pagesVisited: [],
			isTableEmpty: false,
			controller: new AbortController(),
		};
	},
	computed: {
		paginationInfo() {
			if (this.searchName) {
				const result =
					this.searchResults === 1 ? " result" : " results";
				return this.searchResults + result;
			} else {
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
			}
		},
		tableData() {
			return this.searchName ? this.clientSearchList : this.clients;
		},
	},
	async mounted() {
		this.tableContent = await ClientsUtil.fetchPage(1, this.clientsPerPage);
		this.totalClients = await ClientsUtil.getTotalClients();
		this.isTableEmpty = this.totalClients === 0;

		this.pagesVisited.push(1);
		localStorage[`adminlyClientsPage#1`] = JSON.stringify(
			this.tableContent
		);
		this.totalClients = await ClientsUtil.getTotalClients();
		this.totalPages = Math.ceil(this.totalClients / this.clientsPerPage);
	},
	beforeDestroy() {
		this.pagesVisited.forEach((page) => {
			localStorage.removeItem(`adminlyClientsPage#${page}`);
		});
	},
	methods: {
		updateTable() {
			this.tableContent = [];
			this.pagesVisited = [];
			this.totalPages = Math.ceil(
				this.totalClients / this.clientsPerPage
			);
			if (this.currentPage > this.totalPages) this.currentPage = 1;

			this.goToPage = this.currentPage;
			this.getPage(this.currentPage);
		},
		nextPage() {
			if (this.currentPage < this.totalPages && this.currentPage >= 1) {
				this.currentPage += 1;
				this.goToPage = this.currentPage;
				this.getPage(this.currentPage);
			}
		},
		previousPage() {
			if (this.currentPage > 1 && this.currentPage <= this.totalPages) {
				this.currentPage -= 1;
				this.goToPage = this.currentPage;
				this.getPage(this.currentPage);
			}
		},
		getFirstPage() {
			this.getPage(1);
		},
		getLastPage() {
			this.getPage(this.totalPages);
		},
		async getPage(pageNum) {
			if (pageNum <= this.totalPages && pageNum && pageNum > 0) {
				this.searchName = "";
				this.searchResults = 0;
				this.isTableEmpty = false;

				this.currentPage = pageNum;
				this.goToPage = this.currentPage;

				const getFromServer = !this.pagesVisited.includes(pageNum);
				if (getFromServer) {
					this.tableContent = [];
					this.tableContent = await ClientsUtil.fetchPage(
						pageNum,
						this.clientsPerPage
					);
					localStorage[`adminlyClientsPage#${pageNum}`] =
						JSON.stringify(this.tableContent);
					this.pagesVisited.push(pageNum);
				} else {
					this.tableContent = this.getTableFromStorage(pageNum);
				}
			}
		},
		getTableFromStorage(pageNum) {
			return JSON.parse(
				localStorage.getItem(`adminlyClientsPage#${pageNum}`)
			);
		},
		search() {
			this.controller.abort();
			if (this.searchName === "") {
				this.getFirstPage();
			} else if (this.searchName.length > 1) {
				this.tableContent = [];
				this.isTableEmpty = false;
				this.controller = new AbortController();

				const url = "/apps/adminly_clients/searchByName";
				axios
					.get(url, {
						signal: this.controller.signal,
						params: { name: this.searchName },
					})
					.then((response) => {
						this.tableContent = response.data;
						this.currentPage = 1;
						this.goToPage = 1;
						this.searchResults = response.data.length;
						this.isTableEmpty = this.searchResults === 0;
					});
			}
		},
		updateClients(forceRefresh = false) {
			forceRefresh
				? (this.pagesVisited = [])
				: this.pagesVisited.pop(this.currentPage);
			this.getPage(this.currentPage);
		},
		toggleNewClientModal() {
			this.$router.push({
				path: "/new-client",
			});
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
	box-shadow: 0px 0px 10px var(--adminly-box-shadow-color);
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
