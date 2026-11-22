const GRSMS_SYNC_CHANNEL = 'grsms_realtime_sync_v1';
const grsmsChannel = (typeof BroadcastChannel !== 'undefined')
  ? new BroadcastChannel(GRSMS_SYNC_CHANNEL)
  : null;

function notifyDataSynced(topic = 'all') {
  const payload = { topic, ts: Date.now() };
  try {
    if (grsmsChannel) {
      grsmsChannel.postMessage(payload);
    }
  } catch (_) {}
  try {
    localStorage.setItem(GRSMS_SYNC_CHANNEL, JSON.stringify(payload));
  } catch (_) {}
}

function subscribeDataSync(onSync) {
  if (typeof onSync !== 'function') return;

  if (grsmsChannel) {
    grsmsChannel.addEventListener('message', (event) => {
      onSync(event.data || { topic: 'all' });
    });
  }

  window.addEventListener('storage', (event) => {
    if (event.key === GRSMS_SYNC_CHANNEL && event.newValue) {
      try {
        onSync(JSON.parse(event.newValue));
      } catch (_) {
        onSync({ topic: 'all' });
      }
    }
  });
}

async function apiGet(action, params = {}) {
  const qs = new URLSearchParams({ action, ...params, _: String(Date.now()) });
  const res = await fetch(`api/endpoints.php?${qs.toString()}`, { cache: 'no-store' });
  return res.json();
}

async function apiPost(action, payload = {}, syncTopic = 'all') {
  const res = await fetch(`api/endpoints.php?action=${encodeURIComponent(action)}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
  const data = await res.json();
  if (res.ok && data && data.ok !== false) {
    notifyDataSynced(syncTopic);
  }
  return data;
}
