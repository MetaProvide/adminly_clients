<template>
	<div>
		<div v-if="!clients.length">No results</div>
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
								class="mr-10"
							/>
							<p v-if="col === 'timezone'">
								{{
									client.timezone
										? timezoneWithUTC(client.timezone)
										: ""
								}}
							</p>
							<p v-else>
								{{ client[col] }}
							</p>
						</div>
					</td>
					<td>
						<button @click="updateClientModal(client)">
							<svg
								width="18"
								height="19"
								viewBox="0 0 18 19"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M15.5613 0.900391L17.9153 3.25444L9.67656 11.4932L7.32251 9.13915L15.5613 0.900391Z"
									fill="#8E8E8E"
								/>
								<path
									d="M6.94895 9.68283L6.0454 11.7911L5.14186 13.8994L9.35842 12.0923L6.94895 9.68283Z"
									fill="#8E8E8E"
								/>
								<path
									d="M16.1049 7.15289V16.4293H2.61192V2.93633H11.8884V1.2497H2.61192C1.68042 1.2497 0.925293 2.00483 0.925293 2.93633V16.4293C0.925293 17.3608 1.68042 18.116 2.61192 18.116H16.1049C17.0364 18.116 17.7915 17.3608 17.7915 16.4293V7.15289H16.1049Z"
									fill="#8E8E8E"
								/>
							</svg>
						</button>
						<button @click="deleteClientModal(client)">
							<svg
								width="19"
								height="19"
								viewBox="0 0 30 30"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M 13 3 A 1.0001 1.0001 0 0 0 11.986328 4 L 6 4 A 1.0001 1.0001 0 1 0 6 6 L 24 6 A 1.0001 1.0001 0 1 0 24 4 L 18.013672 4 A 1.0001 1.0001 0 0 0 17 3 L 13 3 z M 6 8 L 6 24 C 6 25.105 6.895 26 8 26 L 22 26 C 23.105 26 24 25.105 24 24 L 24 8 L 6 8 z"
									fill="#8E8E8E"
								></path>
							</svg>
						</button>
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
	},
	data() {
		return {
			columns: ["name", "email", "phoneNumber", "timezone"],
			currentClient: {},
			clientModal: false,
			deleteModal: false,
		};
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
	box-shadow: 0px 0px 9px rgba(0, 0, 0, 0.15);
	border-radius: 15px;
	border-style: hidden; /* hide standard table (collapsed) border */
	background-color: white;
}

table th {
	font-weight: bold;
	padding: 10px 20px;
	border: 1 solid #eeeeee;
}

table td {
	text-align: left;
	padding: 10px 20px;
}

table tr {
	border-bottom: 1px solid #eeeeee;
}

table td:last-child {
	border-right: none;
}

table td:first-child {
	color: #238dda;
}

table button {
	background: none;
	border: none;
}

.row {
	display: flex;
	flex-direction: row;
}

.mr-10 {
	margin-right: 10px;
}
</style>
