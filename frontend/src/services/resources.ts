import http from './http';
import type { ApiResponse, PaginatedResponse, QueryParams } from '@/types';

function cleanParams(params?: QueryParams) {
  return Object.fromEntries(
    Object.entries(params ?? {}).filter(([, value]) => value !== undefined && value !== ''),
  );
}

export async function list<T>(url: string, params?: QueryParams): Promise<PaginatedResponse<T>> {
  const response = await http.get<ApiResponse<T[]>>(url, { params: cleanParams(params) });

  return {
    data: response.data.data,
    meta: response.data.meta ?? { current_page: 1, last_page: 1, per_page: 15, total: 0 },
  };
}

export async function collection<T>(url: string, params?: QueryParams): Promise<T[]> {
  const response = await http.get<ApiResponse<T[]>>(url, { params: cleanParams(params) });
  return response.data.data;
}

export async function show<T>(url: string): Promise<T> {
  const response = await http.get<ApiResponse<T>>(url);
  return response.data.data;
}

export async function store<T>(url: string, payload: Record<string, unknown>): Promise<T> {
  const response = await http.post<ApiResponse<T>>(url, payload);
  return response.data.data;
}

export async function update<T>(url: string, payload: Record<string, unknown>): Promise<T> {
  const response = await http.put<ApiResponse<T>>(url, payload);
  return response.data.data;
}

export async function destroy(url: string): Promise<void> {
  await http.delete(url);
}

export const dashboardService = {
  stats: () => show<import('@/types').DashboardStats>('/dashboard/stats'),
  charts: () => show<import('@/types').DashboardCharts>('/dashboard/charts'),
  map: () => show<import('@/types').MapPoint[]>('/dashboard/map'),
};

export const disasterService = {
  list: (params?: QueryParams) => list<import('@/types').DisasterIncident>('/disasters', params),
  show: (id: number | string) => show<import('@/types').DisasterIncident>(`/disasters/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').DisasterIncident>('/disasters', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').DisasterIncident>(`/disasters/${id}`, payload),
  remove: (id: number | string) => destroy(`/disasters/${id}`),
  nearby: (params: { lat: number; lng: number; radius?: number; limit?: number }) =>
    collection<import('@/types').DisasterIncident>('/disasters/nearby', params),
  statistics: () => show<Record<string, number>>('/disasters/statistics'),
  assignResponder: (id: number | string, responder_id: number) =>
    store<import('@/types').DisasterIncident>(`/disasters/${id}/assign-responder`, { responder_id }),
  updateStatus: (id: number | string, status: string) =>
    store<import('@/types').DisasterIncident>(`/disasters/${id}/status`, { status }),
  updateCasualties: (id: number | string, payload: Record<string, unknown>) =>
    store<import('@/types').DisasterIncident>(`/disasters/${id}/casualties`, payload),
};

export const emergencyReportService = {
  list: (params?: QueryParams) => list<import('@/types').EmergencyReport>('/emergency-reports', params),
  show: (id: number | string) => show<import('@/types').EmergencyReport>(`/emergency-reports/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').EmergencyReport>('/emergency-reports', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').EmergencyReport>(`/emergency-reports/${id}`, payload),
  remove: (id: number | string) => destroy(`/emergency-reports/${id}`),
  nearby: (params: { lat: number; lng: number; radius?: number; limit?: number }) =>
    collection<import('@/types').EmergencyReport>('/emergency-reports/nearby', params),
  statistics: () => show<Record<string, number>>('/emergency-reports/statistics'),
  assignResponder: (id: number | string, responder_id: number) =>
    store<import('@/types').EmergencyReport>(`/emergency-reports/${id}/assign-responder`, { responder_id }),
  updateStatus: (id: number | string, status: string) =>
    store<import('@/types').EmergencyReport>(`/emergency-reports/${id}/status`, { status }),
};

export const assistanceRequestService = {
  list: (params?: QueryParams) => list<import('@/types').AssistanceRequest>('/assistance-requests', params),
  show: (id: number | string) => show<import('@/types').AssistanceRequest>(`/assistance-requests/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').AssistanceRequest>('/assistance-requests', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').AssistanceRequest>(`/assistance-requests/${id}`, payload),
  remove: (id: number | string) => destroy(`/assistance-requests/${id}`),
  statistics: () => show<Record<string, number>>('/assistance-requests/statistics'),
  approve: (id: number | string) =>
    store<import('@/types').AssistanceRequest>(`/assistance-requests/${id}/approve`, {}),
  dispatch: (id: number | string) =>
    store<import('@/types').AssistanceRequest>(`/assistance-requests/${id}/dispatch`, {}),
  complete: (id: number | string) =>
    store<import('@/types').AssistanceRequest>(`/assistance-requests/${id}/complete`, {}),
  reject: (id: number | string, rejection_reason: string) =>
    store<import('@/types').AssistanceRequest>(`/assistance-requests/${id}/reject`, { rejection_reason }),
};

export const evacuationCenterService = {
  list: (params?: QueryParams) => list<import('@/types').EvacuationCenter>('/evacuation-centers', params),
  show: (id: number | string) => show<import('@/types').EvacuationCenter>(`/evacuation-centers/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').EvacuationCenter>('/evacuation-centers', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').EvacuationCenter>(`/evacuation-centers/${id}`, payload),
  remove: (id: number | string) => destroy(`/evacuation-centers/${id}`),
  nearby: (params: { lat: number; lng: number; radius?: number; limit?: number }) =>
    collection<import('@/types').EvacuationCenter>('/evacuation-centers/nearby', params),
  statistics: () => show<Record<string, number>>('/evacuation-centers/statistics'),
  updateOccupancy: (id: number | string, occupancy: number) =>
    store<import('@/types').EvacuationCenter>(`/evacuation-centers/${id}/occupancy`, { occupancy }),
};

export const reliefItemService = {
  list: (params?: QueryParams) => list<import('@/types').ReliefItem>('/relief-items', params),
  show: (id: number | string) => show<import('@/types').ReliefItem>(`/relief-items/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').ReliefItem>('/relief-items', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').ReliefItem>(`/relief-items/${id}`, payload),
  remove: (id: number | string) => destroy(`/relief-items/${id}`),
  statistics: () => show<Record<string, number>>('/relief-items/statistics'),
  lowStock: () => collection<import('@/types').ReliefItem>('/relief-items/low-stock'),
  expiringSoon: (days = 30) => collection<import('@/types').ReliefItem>('/relief-items/expiring-soon', { days }),
  stockIn: (id: number | string, payload: Record<string, unknown>) =>
    store<import('@/types').InventoryTransaction>(`/relief-items/${id}/stock-in`, payload),
  stockOut: (id: number | string, payload: Record<string, unknown>) =>
    store<import('@/types').InventoryTransaction>(`/relief-items/${id}/stock-out`, payload),
  distribute: (id: number | string, payload: Record<string, unknown>) =>
    store<import('@/types').InventoryTransaction>(`/relief-items/${id}/distribute`, payload),
  transactions: (id: number | string, params?: QueryParams) =>
    list<import('@/types').InventoryTransaction>(`/relief-items/${id}/transactions`, params),
};

export const responderService = {
  list: (params?: QueryParams) => list<import('@/types').Responder>('/responders', params),
  show: (id: number | string) => show<import('@/types').Responder>(`/responders/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').Responder>('/responders', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').Responder>(`/responders/${id}`, payload),
  remove: (id: number | string) => destroy(`/responders/${id}`),
  statistics: () => show<Record<string, number>>('/responders/statistics'),
  updateStatus: (id: number | string, status: string) =>
    store<import('@/types').Responder>(`/responders/${id}/status`, { status }),
};

export const volunteerService = {
  list: (params?: QueryParams) => list<import('@/types').Volunteer>('/volunteers', params),
  show: (id: number | string) => show<import('@/types').Volunteer>(`/volunteers/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').Volunteer>('/volunteers', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').Volunteer>(`/volunteers/${id}`, payload),
  remove: (id: number | string) => destroy(`/volunteers/${id}`),
  statistics: () => show<Record<string, number>>('/volunteers/statistics'),
  updateStatus: (id: number | string, status: string) =>
    store<import('@/types').Volunteer>(`/volunteers/${id}/status`, { status }),
};

export const volunteerAssignmentService = {
  list: (params?: QueryParams) => list<import('@/types').VolunteerAssignment>('/volunteer-assignments', params),
  show: (id: number | string) => show<import('@/types').VolunteerAssignment>(`/volunteer-assignments/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').VolunteerAssignment>('/volunteer-assignments', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').VolunteerAssignment>(`/volunteer-assignments/${id}`, payload),
  remove: (id: number | string) => destroy(`/volunteer-assignments/${id}`),
};

export const affectedPersonService = {
  list: (params?: QueryParams) => list<import('@/types').AffectedPerson>('/affected-persons', params),
  show: (id: number | string) => show<import('@/types').AffectedPerson>(`/affected-persons/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').AffectedPerson>('/affected-persons', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').AffectedPerson>(`/affected-persons/${id}`, payload),
  remove: (id: number | string) => destroy(`/affected-persons/${id}`),
  statistics: () => show<Record<string, number>>('/affected-persons/statistics'),
  updateCondition: (id: number | string, condition: string) =>
    store<import('@/types').AffectedPerson>(`/affected-persons/${id}/condition`, { condition }),
  assignCenter: (id: number | string, evacuation_center_id: number) =>
    store<import('@/types').AffectedPerson>(`/affected-persons/${id}/assign-center`, { evacuation_center_id }),
};

export const announcementService = {
  list: (params?: QueryParams) => list<import('@/types').Announcement>('/announcements', params),
  show: (id: number | string) => show<import('@/types').Announcement>(`/announcements/${id}`),
  create: (payload: Record<string, unknown>) =>
    store<import('@/types').Announcement>('/announcements', payload),
  update: (id: number | string, payload: Record<string, unknown>) =>
    update<import('@/types').Announcement>(`/announcements/${id}`, payload),
  remove: (id: number | string) => destroy(`/announcements/${id}`),
  statistics: () => show<Record<string, number>>('/announcements/statistics'),
  publish: (id: number | string) =>
    store<import('@/types').Announcement>(`/announcements/${id}/publish`, {}),
};

export const auditLogService = {
  list: (params?: QueryParams) => list<import('@/types').AuditLog>('/audit-logs', params),
  statistics: () => show<Record<string, number>>('/audit-logs/statistics'),
};
