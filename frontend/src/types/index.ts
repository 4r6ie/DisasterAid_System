export interface User {
  id: number;
  name: string;
  email: string;
  phone?: string | null;
  address?: string | null;
  avatar?: string | null;
  roles?: string[];
  permissions?: string[];
  email_verified_at?: string | null;
  created_at?: string;
}

export interface PaginatedMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: PaginatedMeta;
}

export interface ApiResponse<T> {
  success: boolean;
  message?: string;
  data: T;
  errors?: Record<string, string[]>;
  meta?: PaginatedMeta;
}

export interface AuditLog {
  id: number;
  log_name?: string;
  description: string;
  subject_type?: string | null;
  subject_id?: number | null;
  causer_type?: string | null;
  causer_id?: number | null;
  event?: string | null;
  properties?: Record<string, unknown>;
  created_at: string;
}

export interface DisasterIncident {
  id: number;
  incident_id: string;
  disaster_type: string;
  title: string;
  description?: string | null;
  severity: Severity;
  status: string;
  latitude?: number | null;
  longitude?: number | null;
  address?: string | null;
  city?: string | null;
  province?: string | null;
  affected_count: number;
  injured_count: number;
  missing_count: number;
  deceased_count: number;
  rescued_count: number;
  occurred_at?: string | null;
  resolved_at?: string | null;
  reporter?: User | null;
  assigned_responder?: Responder | null;
  emergencies?: EmergencyReport[];
  assistance_requests?: AssistanceRequest[];
  affected_persons?: AffectedPerson[];
  announcements?: Announcement[];
  created_at?: string;
  updated_at?: string;
}

export interface EmergencyReport {
  id: number;
  emergency_type: string;
  priority: Priority;
  status: string;
  description?: string | null;
  latitude?: number | null;
  longitude?: number | null;
  address?: string | null;
  people_needing_help: number;
  contact_name?: string | null;
  contact_phone?: string | null;
  disaster_incident?: DisasterIncident | null;
  reporter?: User | null;
  assigned_responder?: Responder | null;
  assigned_at?: string | null;
  resolved_at?: string | null;
  resolution_notes?: string | null;
  created_at?: string;
  updated_at?: string;
}

export interface AssistanceRequest {
  id: number;
  request_number: string;
  assistance_type: string;
  quantity: number;
  unit: string;
  urgency: Priority;
  status: string;
  description?: string | null;
  latitude?: number | null;
  longitude?: number | null;
  address?: string | null;
  contact_name?: string | null;
  contact_phone?: string | null;
  disaster_incident?: DisasterIncident | null;
  requester?: User | null;
  approver?: User | null;
  dispatcher?: User | null;
  approved_at?: string | null;
  dispatched_at?: string | null;
  completed_at?: string | null;
  rejection_reason?: string | null;
  metadata?: Record<string, unknown> | null;
  created_at?: string;
  updated_at?: string;
}

export interface EvacuationCenter {
  id: number;
  name: string;
  status: string;
  latitude?: number | null;
  longitude?: number | null;
  address?: string | null;
  city?: string | null;
  province?: string | null;
  capacity: number;
  current_occupancy: number;
  facilities?: string[] | null;
  contact_person?: string | null;
  contact_phone?: string | null;
  contact_email?: string | null;
  notes?: string | null;
  manager?: User | null;
  affected_persons?: AffectedPerson[];
  created_at?: string;
  updated_at?: string;
}

export interface ReliefItem {
  id: number;
  name: string;
  category: string;
  unit: string;
  quantity: number;
  min_stock: number;
  unit_cost?: number | null;
  location?: string | null;
  expiry_date?: string | null;
  batch_number?: string | null;
  description?: string | null;
  status: string;
  transactions?: InventoryTransaction[];
  created_at?: string;
  updated_at?: string;
}

export interface InventoryTransaction {
  id: number;
  relief_item?: ReliefItem;
  type: 'in' | 'out' | 'distribute' | 'adjustment' | 'expired' | 'damaged' | 'returned';
  quantity: number;
  balance?: number;
  evacuation_center?: EvacuationCenter | null;
  disaster_incident?: DisasterIncident | null;
  processed_by?: User | null;
  remarks?: string | null;
  transaction_date?: string | null;
  created_at?: string;
}

export interface Responder {
  id: number;
  employee_id: string;
  name: string;
  email: string;
  phone?: string | null;
  specialization: string;
  rank?: string | null;
  status: string;
  address?: string | null;
  date_of_birth?: string | null;
  certifications?: string[] | null;
  equipment?: string[] | null;
  hired_at?: string | null;
  assigned_disasters_count?: number;
  assigned_emergencies_count?: number;
  created_at?: string;
  updated_at?: string;
}

export interface Volunteer {
  id: number;
  name: string;
  email: string;
  phone?: string | null;
  address?: string | null;
  date_of_birth?: string | null;
  gender?: string | null;
  skills?: string[] | null;
  availability?: Record<string, string[]> | null;
  status: string;
  emergency_contact_name?: string | null;
  emergency_contact_phone?: string | null;
  notes?: string | null;
  joined_at?: string;
  assignments_count?: number;
  active_assignments_count?: number;
  created_at?: string;
  updated_at?: string;
}

export interface VolunteerAssignment {
  id: number;
  volunteer?: Volunteer | null;
  disaster_incident?: DisasterIncident | null;
  role?: string | null;
  status: string;
  assigned_by?: User | null;
  started_at?: string | null;
  completed_at?: string | null;
  notes?: string | null;
  created_at?: string;
  updated_at?: string;
}

export interface AffectedPerson {
  id: number;
  name: string;
  age?: number | null;
  gender?: string | null;
  condition: string;
  condition_label?: string;
  contact_phone?: string | null;
  address?: string | null;
  medical_notes?: string | null;
  family_contact_name?: string | null;
  family_contact_phone?: string | null;
  evacuated_at?: string | null;
  notes?: string | null;
  disaster_incident?: DisasterIncident | null;
  evacuation_center?: EvacuationCenter | null;
  registrar?: User | null;
  created_at?: string;
  updated_at?: string;
}

export interface Announcement {
  id: number;
  title: string;
  content: string;
  type: string;
  priority: Priority;
  audience: string;
  is_published: boolean;
  is_active?: boolean;
  published_at?: string | null;
  expires_at?: string | null;
  metadata?: Record<string, unknown> | null;
  disaster_incident?: DisasterIncident | null;
  creator?: User | null;
  created_at?: string;
  updated_at?: string;
}

export type Severity = 'low' | 'moderate' | 'high' | 'critical';
export type Priority = 'low' | 'medium' | 'high' | 'critical';

export interface DashboardStats {
  disasters: { total: number; active: number; critical: number; this_month: number };
  emergencies: { total: number; pending: number; in_progress: number; critical: number };
  assistance: { total: number; pending: number; dispatched: number; urgent: number };
  evacuation: { total_centers: number; active_centers: number; total_capacity: number; current_occupancy: number };
  inventory: { total_items: number; low_stock: number; expiring_soon: number; total_value: number };
  people: { affected: number; in_evacuation: number; injured: number; missing: number; volunteers: number; responders: number };
}

export interface DashboardCharts {
  disaster_trends: Record<string, number>;
  disaster_by_type: Record<string, number>;
  disaster_by_severity: Record<string, number>;
  emergencies_by_priority: Record<string, number>;
  assistance_by_type: Record<string, number>;
  occupancy_trend: Record<string, { occupancy: number; capacity: number }>;
}

export interface MapPoint {
  id: number;
  title: string;
  type: 'disaster' | 'emergency' | 'evacuation_center';
  subtype?: string;
  severity?: string;
  priority?: string;
  status: string;
  lat: number;
  lng: number;
  count?: number;
  capacity?: number;
  occupancy?: number;
}

export interface QueryParams {
  page?: number;
  per_page?: number;
  search?: string;
  sort_by?: string;
  sort_dir?: 'asc' | 'desc';
  [key: string]: string | number | boolean | undefined;
}
