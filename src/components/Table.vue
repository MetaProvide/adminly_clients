<template>
	<div>
		<div v-if="shouldLoadTable" class="loading"></div>
		<div v-else-if="isEmpty">No results</div>
		<table v-else>
			<thead>
				<tr>
					<th>Client Name</th>
					<th>Email</th>
					<th>Phone Number</th>
					<th>Timezone</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="client in clients" :key="client.username">
					<td v-for="col in columns" :key="col.label">
						<div class="row">
							<Avatar
								v-if="col === 'name'"
								:username="client.name"
								:size="30"
								class="avatar"
							/>
							<p v-if="col === 'timezone'">
								{{
									client.timezone
										? timezoneWithUTC(client.timezone)
										: ""
								}}
							</p>
							<p v-else>
								{{
									client[col].length > 22
										? client[col].slice(0, 22) + "..."
										: client[col]
								}}
							</p>
						</div>
					</td>
					<td>
						<button
							class="edit-button"
							@click="updateClientModal(client)"
						></button>
						<button
							class="delete-button"
							@click="deleteClientModal(client)"
						></button>
					</td>
				</tr>
			</tbody>
		</table>
		<ClientModal
			v-if="clientModal"
			:client="currentClient"
			@toggle-modal="toggleClientModal"
			@update-clients="updateClients"
		/>
		<ClientDeletion
			v-if="deleteModal"
			:client="currentClient"
			@toggle-modal="toggleDeleteModal"
			@update-clients="updateClients"
		/>
	</div>
</template>

<script>
import ClientModal from "./ClientModal";
import Avatar from "vue-avatar";
import ClientDeletion from "./ClientDeletion";
import { TimezoneUtil } from "../utils";

export default {
	components: {
		ClientModal,
		Avatar,
		ClientDeletion,
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
			columns: ["name", "email", "phoneNumber", "timezone"],
			currentClient: {},
			clientModal: false,
			deleteModal: false,
		};
	},
	computed: {
		shouldLoadTable() {
			return !this.isEmpty && !this.clients.length;
		},
	},
	methods: {
		updateClientModal(client) {
			this.currentClient = client;
			this.toggleClientModal();
		},
		toggleClientModal() {
			this.clientModal = !this.clientModal;
		},
		toggleDeleteModal() {
			this.deleteModal = !this.deleteModal;
		},
		updateClients() {
			this.$emit("update-clients", true);
		},
		deleteClientModal(client) {
			this.currentClient = client;
			this.toggleDeleteModal();
		},
		timezoneWithUTC(timezone) {
			return TimezoneUtil.timezoneWithUTC(timezone);
		},
	},
};
</script>

<style scoped>
table {
	width: 100%;
	border-collapse: collapse;
	box-shadow: 0px 0px 9px var(--adminly-grey);
	border-radius: 15px;
	border-style: hidden; /* hide standard table (collapsed) border */
	background-color: white;
	color: var(--neutral-600);
}

table th {
	font-weight: bold;
	padding: 0.7rem 1.25rem;
	border: 1 solid var(--adminly-line-color);
}

table td {
	text-align: left;
	padding: 0.7rem 1.25rem;
}

table tr {
	border-bottom: 1px solid var(--adminly-line-color);
}

table td:last-child {
	border-right: none;
}

table td:first-child {
	color: var(--adminly-light-blue);
}

table td:first-child p {
	line-height: 30px;
}

table button {
	border: none;
	background-color: white;
}

.row {
	display: flex;
	flex-direction: row;
}

.avatar {
	margin-right: 0.7rem;
}
</style>
