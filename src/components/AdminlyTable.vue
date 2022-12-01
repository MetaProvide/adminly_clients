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
					<th>Last Session</th>
					<th>Next Session</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="client in clients" :key="client.username">
					<td v-for="col in columns" :key="col.label">
						<div class="row">
							<Avatar
								v-if="col === 'name'"
								:username="getFirstAndLastName(client.name)"
								:size="30"
								class="avatar"
								:class="getAdminlyColor(client.name)"
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
					<td>{{ formatDate(client.lastSession) }}</td>
					<td>{{ formatDate(client.nextSession) }}</td>
					<td class="action-buttons">
						<button
							class="svg open-button"
							@click="openClientModal(client.id)"
						></button>
						<a
							class="svg email-button"
							:href="'mailto:' + client.email"
						></a>
						<button
							class="svg delete-button"
							@click="deleteClientModal(client)"
						></button>
					</td>
				</tr>
			</tbody>
		</table>
		<ClientDeletion
			v-if="deleteModal"
			:client="currentClient"
			@toggle-modal="toggleDeleteModal"
			@update-clients="updateClients(true)"
		/>
	</div>
</template>

<script>
import Avatar from "vue-avatar";
import ClientDeletion from "./ClientDeletion.vue";
import { TimezoneUtil } from "../utils.js";
import dayjs from "dayjs";

export default {
	components: {
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
		openClientModal(clientId) {
			this.$router.push({
				path: `/client/${clientId}`,
			});
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
		formatDate(date) {
			if (date) return dayjs(date).format("DD MMM, hh:mm");
		},
		getAdminlyColor(name) {
			const index = name.length % 12;
			return `adminly-avatar-${index}`;
		},
		getFirstAndLastName(fullName) {
			const fullNameArray = fullName.split(" ");
			const firstName = fullNameArray[0];
			const lastName = fullNameArray.pop();
			return firstName === lastName
				? firstName
				: `${firstName} ${lastName}`;
		},
	},
};
</script>

<style scoped>
table {
	width: 100%;
	border-collapse: collapse;
	box-shadow: 0px 0px 9px var(--adminly-box-shadow-color);
	border-radius: 15px;
	border-style: hidden;
	/* hide standard table (collapsed) border */
	background-color: white;
	color: var(--adminly-dark-blue);
}

table th {
	font-size: 1rem;
	padding: 1rem 1.25rem;
	font-weight: 700;
}

table td {
	text-align: left;
	padding: 0.7rem 1.25rem;
	font-weight: 500;
}

table tr {
	border-bottom: 1px solid var(--adminly-line-color);
}

tr:hover button {
	background-color: var(--color-background-dark);
}

table td:last-child {
	border-right: none;
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

.email-button {
	margin: 3px;
	width: auto;
}

.delete-button {
	background-size: 19px;
	background-position-y: 7px;
}

.action-buttons {
	display: flex;
}

.svg {
	transition: none;
}
</style>
