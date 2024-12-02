<template>
  <div class="space-y-6">
    <h1 class="text-2xl font-bold">Settings</h1>

    <div class="card">
      <h2 class="text-xl font-semibold mb-4">General Settings</h2>

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Admin Email
          </label>
          <input type="email" class="input" value="admin@example.com" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Ranking Update Frequency
          </label>
          <select class="input">
            <option>Daily</option>
            <option>Weekly</option>
            <option>Monthly</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Notification Preferences
          </label>
          <div class="space-y-2">
            <label class="flex items-center gap-2">
              <input type="checkbox" checked />
              Email notifications
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" checked />
              Ranking change alerts
            </label>
          </div>
        </div>

        <div class="pt-4 flex justify-between items-center">
          <button class="btn btn-primary">Save Changes</button>
          <button class="btn text-white bg-red-600">Logout</button>
        </div>
      </div>
    </div>
  </div>
</template>
