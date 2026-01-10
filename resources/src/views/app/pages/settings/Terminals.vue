<template>
  <div class="main-content">
    <breadcumb :page="'POS Terminals'" :folder="'Settings'" />

    <div v-if="isLoading" class="loading_spinner timer-loader"></div>

    <div v-else>
      <div v-if="error" class="alert alert-danger">{{ error }}</div>

      <!-- License Status Card -->
      <b-row>
        <b-col md="12">
          <b-card class="mb-30">
            <b-row>
              <b-col md="8">
                <h3>Terminal Licenses</h3>
                <b-progress :value="activeCount" :max="maxClients" show-progress animated variant="success" class="mb-2"></b-progress>
                <p>
                  You are using <strong>{{ activeCount }}</strong> out of <strong>{{ maxClients }}</strong> allowed POS Terminals.
                  <span v-if="remaining > 0" class="text-success">({{ remaining }} slots remaining)</span>
                  <span v-else class="text-danger">(Limit Reached)</span>
                </p>
              </b-col>
              <b-col md="4" class="text-right">
                <b-button v-if="remaining > 0" variant="primary" @click="showModal">
                  <i class="i-Add-User"></i> Authorize New Terminal
                </b-button>
                 <b-button v-else variant="secondary" disabled>
                  Limit Reached
                </b-button>
              </b-col>
            </b-row>
          </b-card>
        </b-col>
      </b-row>

      <!-- Terminals Table -->
      <b-row>
        <b-col md="12">
          <b-card body-class="p-0" title="Authorized Terminals">
            <b-table
              responsive
              striped
              hover
              :items="terminals"
              :fields="columns"
              show-empty
            >
              <template #cell(status)="data">
                <span v-if="data.item.is_active" class="badge badge-success">Active</span>
                <span v-else class="badge badge-danger">Banned</span>
              </template>

              <template #cell(last_seen)="data">
                <span v-if="data.item.last_seen_at">{{ data.item.last_seen_at }}</span>
                 <span v-else class="text-muted">Never</span>
              </template>

              <template #cell(actions)="data">
                <b-button variant="danger" size="sm" @click="deleteTerminal(data.item.id)">
                  <i class="i-Close-Window"></i> Remove
                </b-button>
              </template>
            </b-table>
          </b-card>
        </b-col>
      </b-row>

      <!-- Add Modal -->
      <b-modal id="terminal-modal" title="Authorize New POS Terminal" hide-footer>
        <b-form @submit.prevent="submitTerminal">
          <b-form-group label="Terminal Name (e.g. Cashier 1)">
            <b-form-input v-model="form.name" required placeholder="Enter a friendly name" />
          </b-form-group>
          
          <b-form-group label="Hardware ID (HWID)">
            <b-form-input v-model="form.hwid" required placeholder="Enter the HWID shown on the client screen" />
            <small class="text-muted">Found on the Sela Client splash screen.</small>
          </b-form-group>

          <div class="text-right">
             <b-button type="submit" variant="primary" :disabled="submitLoading">
                {{ submitLoading ? 'Authorizing...' : 'Authorize' }}
             </b-button>
          </div>
        </b-form>
      </b-modal>

    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { mapActions, mapGetters } from "vuex";

export default {
  data() {
    return {
      isLoading: true,
      submitLoading: false,
      terminals: [],
      maxClients: 0,
      activeCount: 0,
      remaining: 0,
      error: null,
      form: {
        name: '',
        hwid: ''
      },
      columns: [
        { key: 'name', label: 'Terminal Name' },
        { key: 'hwid', label: 'Hardware ID' },
        { key: 'last_ip', label: 'Last IP' },
        { key: 'last_seen', label: 'Last Seen' },
        { key: 'status', label: 'Status' },
        { key: 'actions', label: 'Actions' }
      ]
    };
  },
  methods: {
    fetchData() {
      this.isLoading = true;
      axios.get('/admin/terminals')
        .then(response => {
          const data = response.data;
          this.terminals = data.terminals;
          this.maxClients = data.max_clients;
          this.activeCount = data.active_count;
          this.remaining = data.remaining;
        })
        .catch(err => {
          this.error = "Failed to load terminals. " + (err.response?.data?.message || err.message);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    showModal() {
      this.form.name = '';
      this.form.hwid = '';
      this.$bvModal.show('terminal-modal');
    },
    submitTerminal() {
      this.submitLoading = true;
      axios.post('/admin/terminals', this.form)
        .then(() => {
          this.$bvModal.hide('terminal-modal');
          this.makeToast("success", "Terminal authorized successfully", "Success");
          this.fetchData();
        })
        .catch(err => {
           this.makeToast("danger", err.response?.data?.message || "Failed to add terminal", "Error");
        })
        .finally(() => {
          this.submitLoading = false;
        });
    },
    deleteTerminal(id) {
        this.$swal({
        title: "Are you sure?",
        text: "This terminal will be de-authorized.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          axios.get('/admin/terminals/delete/' + id)
            .then(() => {
              this.makeToast("success", "Terminal removed", "Deleted");
              this.fetchData();
            })
            .catch(err => {
              this.makeToast("danger", "Failed to remove terminal", "Error");
            });
        }
      });
    },
    makeToast(variant = null, msg, title) {
      this.$bvToast.toast(msg, {
        title: title,
        variant: variant,
        solid: true
      });
    }
  },
  created() {
    this.fetchData();
  }
};
</script>
