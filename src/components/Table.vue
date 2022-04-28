<template>
	<div>
		<table>
			<thead>
				<tr>
					<th>Client Name</th>
					<th>Email</th>
					<th>Description</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="client in clients" :key="client.username">
					<td v-for="col in columns" :key="col.label">
						{{ client[col] }}
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
						<button>
							<svg
								width="4"
								height="16"
								viewBox="0 0 4 16"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M0.270549 1.7794C0.270549 2.7109 1.02568 3.46602 1.95717 3.46602C2.88867 3.46602 3.6438 2.7109 3.6438 1.7794C3.6438 0.847901 2.88867 0.0927734 1.95717 0.0927734C1.02568 0.0927733 0.270549 0.847901 0.270549 1.7794Z"
									fill="#8E8E8E"
								/>
								<path
									d="M1.95717 9.36921C1.02568 9.36921 0.270548 8.61408 0.270548 7.68259C0.270549 6.75109 1.02568 5.99596 1.95717 5.99596C2.88867 5.99596 3.6438 6.75109 3.6438 7.68259C3.6438 8.61408 2.88867 9.36921 1.95717 9.36921Z"
									fill="#8E8E8E"
								/>
								<path
									d="M1.95717 15.2724C1.02568 15.2724 0.270548 14.5173 0.270548 13.5858C0.270548 12.6543 1.02568 11.8991 1.95717 11.8991C2.88867 11.8991 3.6438 12.6543 3.6438 13.5858C3.6438 14.5173 2.88867 15.2724 1.95717 15.2724Z"
									fill="#8E8E8E"
								/>
							</svg>
						</button>
					</td>
				</tr>
			</tbody>
		</table>
		<ClientModal
			v-if="modal"
			:client="currentClient"
			@toggle-modal="toggleModal"
		/>
	</div>
</template>

<script>
import ClientModal from "./ClientModal";

export default {
	components: {
		ClientModal,
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
			columns: ["name", "email", "description"],
			currentClient: {},
			modal: false,
		};
	},
	methods: {
		updateClientModal(client) {
			this.currentClient = client;
			this.toggleModal();
		},
		toggleModal() {
			this.modal = !this.modal;
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
</style>
