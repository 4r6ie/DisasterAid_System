import { createBrowserRouter, Navigate } from 'react-router-dom';
import { Layout } from '@/layouts/Layout';
import { GuestRoute, ProtectedRoute } from '@/components/shared/ProtectedRoute';
import { LoginPage } from '@/pages/LoginPage';
import { RegisterPage } from '@/pages/RegisterPage';
import { DashboardPage } from '@/pages/DashboardPage';
import { DisasterIncidentsPage } from '@/pages/DisasterIncidentsPage';
import { EmergencyReportsPage } from '@/pages/EmergencyReportsPage';
import { AssistanceRequestsPage } from '@/pages/AssistanceRequestsPage';
import { EvacuationCentersPage } from '@/pages/EvacuationCentersPage';
import { ReliefItemsPage } from '@/pages/ReliefItemsPage';
import { RespondersPage } from '@/pages/RespondersPage';
import { VolunteersPage } from '@/pages/VolunteersPage';
import { VolunteerAssignmentsPage } from '@/pages/VolunteerAssignmentsPage';
import { AffectedPersonsPage } from '@/pages/AffectedPersonsPage';
import { AnnouncementsPage } from '@/pages/AnnouncementsPage';
import { AuditLogsPage } from '@/pages/AuditLogsPage';
import { ProfilePage } from '@/pages/ProfilePage';
import { NotFoundPage } from '@/pages/NotFoundPage';

export const router = createBrowserRouter([
  {
    path: '/login',
    element: (
      <GuestRoute>
        <LoginPage />
      </GuestRoute>
    ),
  },
  {
    path: '/register',
    element: (
      <GuestRoute>
        <RegisterPage />
      </GuestRoute>
    ),
  },
  {
    path: '/',
    element: (
      <ProtectedRoute>
        <Layout />
      </ProtectedRoute>
    ),
    children: [
      { index: true, element: <Navigate to="/dashboard" replace /> },
      { path: 'dashboard', element: <DashboardPage /> },
      { path: 'disasters', element: <DisasterIncidentsPage /> },
      { path: 'emergency-reports', element: <EmergencyReportsPage /> },
      { path: 'assistance-requests', element: <AssistanceRequestsPage /> },
      { path: 'evacuation-centers', element: <EvacuationCentersPage /> },
      { path: 'relief-items', element: <ReliefItemsPage /> },
      { path: 'responders', element: <RespondersPage /> },
      { path: 'volunteers', element: <VolunteersPage /> },
      { path: 'volunteer-assignments', element: <VolunteerAssignmentsPage /> },
      { path: 'affected-persons', element: <AffectedPersonsPage /> },
      { path: 'announcements', element: <AnnouncementsPage /> },
      { path: 'audit-logs', element: <AuditLogsPage /> },
      { path: 'profile', element: <ProfilePage /> },
      { path: '*', element: <NotFoundPage /> },
    ],
  },
]);
