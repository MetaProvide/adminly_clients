<template>
	<div class="card">
		<div class="card-content">
			<span class="card-header">
				<slot name="title"></slot>
			</span>
			<div class="card-data">
				<p>
					{{ totalCount }}
					<span>
						{{ totalPercentageChanges }}%
						<svg
							width="11"
							height="6"
							viewBox="0 0 11 6"
							fill="none"
							xmlns="http://www.w3.org/2000/svg"
						>
							<path
								d="M0.988891 4.93102L5.22483 0.714441C5.4429 0.497367 5.79646 0.497367 6.01453 0.714441L10.2505 4.93102C10.6022 5.28119 10.3531 5.87992 9.85561 5.87992H1.38374C0.886258 5.87992 0.637116 5.28119 0.988891 4.93102Z"
								fill="#47B881"
							/>
						</svg>
					</span>
				</p>
			</div>
			<hr />
			<div class="card-history">
				<div class="card-history-entry history-yesterday">
					<span>Yesterday</span>
					<span>
						{{ yesterdayCount }} ({{ yesterdayPercentageChange }}
						%)
					</span>
				</div>
				<div class="card-history-entry history-last-week">
					<span>Last week</span>
					<span>
						{{ lastWeekCount }} ( {{ lastWeekPercentageChange }} %)
					</span>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "CardOverview",
	props: {
		totalCount: {
			type: Float32Array,
			default: 0,
		},
		totalPercentageChanges: {
			type: Float32Array,
			default: 0.0,
		},
		yesterdayCount: {
			type: Float32Array,
			default: 0,
		},
		yesterdayPercentageChange: {
			type: Float32Array,
			default: 0,
		},
		lastWeekCount: {
			type: Float32Array,
			default: 0,
		},
		lastWeekPercentageChange: {
			type: Float32Array,
			default: 0,
		},
	},
};
</script>

<style lang="css" scoped>
.card {
	box-shadow: 0 0 11px rgba(0, 0, 0, 0.15);
	border-radius: 1rem;
	flex: 1;
	margin-right: 2rem;
}

.card:last-of-type {
	margin-right: 0;
}

.card-content {
	padding: 1.1rem;
}

.card-header {
	font-size: 0.8rem;
	line-height: 1.2rem;
}

.card-data p {
	font-weight: 700;
	font-size: 2rem;
	line-height: 2.5rem;
	color: #1f1f1f;
}

.card-data span {
	font-weight: 400;
	font-size: 0.8rem;
	color: #8e8e8e;
}

hr {
	border-color: #eee;
}

.card-history {
	display: flex;
	flex-direction: column;
	font-size: 0.8rem;
}

.card-history .card-history-entry {
	display: flex;
	justify-content: space-between;
}
</style>
