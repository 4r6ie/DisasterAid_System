import { ComponentType, useState } from 'react';
import { NavLink, Outlet, useLocation, useNavigate } from 'react-router-dom';
import {
  Activity,
  AlertTriangle,
  Building2,
  ClipboardList,
  HandHelping,
  HeartHandshake,
  History,
  LayoutDashboard,
  LogOut,
  Menu,
  Package,
  Megaphone,
  ShieldAlert,
  Siren,
  Users,
  X,
} from 'lucide-react';
import { useAuthStore } from '@/stores/auth.store';
import { useAuthorization } from '@/hooks/useAuthorization';
import { cn } from '@/utils/cn';
import { initials } from '@/utils/format';

interface NavItem {
  label: string;
  to: string;
  icon: ComponentType<{ className?: string }>;
  permission?: string;
}

const navItems: NavItem[] = [
  { label: 'Dashboard', to: '/dashboard', icon: LayoutDashboard, permission: 'view dashboard' },
  { label: 'Disaster Incidents', to: '/disasters', icon: AlertTriangle, permission: 'view disasters' },
  { label: 'Emergency Reports', to: '/emergency-reports', icon: Siren, permission: 'view emergencies' },
  { label: 'Assistance Requests', to: '/assistance-requests', icon: HandHelping, permission: 'view assistance requests' },
  { label: 'Evacuation Centers', to: '/evacuation-centers', icon: Building2, permission: 'view evacuation centers' },
  { label: 'Relief Inventory', to: '/relief-items', icon: Package, permission: 'view relief inventory' },
  { label: 'Responders', to: '/responders', icon: ShieldAlert, permission: 'view responders' },
  { label: 'Volunteers', to: '/volunteers', icon: HeartHandshake, permission: 'view volunteers' },
  { label: 'Assignments', to: '/volunteer-assignments', icon: ClipboardList, permission: 'view volunteers' },
  { label: 'Affected Persons', to: '/affected-persons', icon: Users, permission: 'view affected persons' },
  { label: 'Announcements', to: '/announcements', icon: Megaphone, permission: 'view announcements' },
  { label: 'Audit Logs', to: '/audit-logs', icon: History, permission: 'view audit logs' },
];

const pageTitles: Array<{ match: string; title: string }> = [
  { match: '/dashboard', title: 'Dashboard' },
  { match: '/disasters', title: 'Disaster Incidents' },
  { match: '/emergency-reports', title: 'Emergency Reports' },
  { match: '/assistance-requests', title: 'Assistance Requests' },
  { match: '/evacuation-centers', title: 'Evacuation Centers' },
  { match: '/relief-items', title: 'Relief Inventory' },
  { match: '/responders', title: 'Responders' },
  { match: '/volunteers', title: 'Volunteers' },
  { match: '/volunteer-assignments', title: 'Volunteer Assignments' },
  { match: '/affected-persons', title: 'Affected Persons' },
  { match: '/announcements', title: 'Announcements' },
  { match: '/audit-logs', title: 'Audit Logs' },
  { match: '/profile', title: 'My Profile' },
];

function SidebarContent({ onNavigate }: { onNavigate?: () => void }) {
  const { can } = useAuthorization();

  return (
    <div className="flex h-full flex-col">
      <div className="flex h-16 items-center gap-2 border-b border-slate-200 px-5">
        <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-600 text-white">
          <Activity className="h-5 w-5" />
        </div>
        <div>
          <p className="text-sm font-semibold text-slate-900">DisasterAid</p>
          <p className="text-xs text-slate-500">Emergency Response</p>
        </div>
      </div>

      <nav className="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        {navItems
          .filter((item) => can(item.permission))
          .map((item) => (
            <NavLink
              key={item.to}
              to={item.to}
              onClick={onNavigate}
              className={({ isActive }) =>
                cn(
                  'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                  isActive
                    ? 'bg-primary-50 text-primary-700'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900',
                )
              }
            >
              <item.icon className="h-5 w-5" />
              {item.label}
            </NavLink>
          ))}
      </nav>
    </div>
  );
}

export function Layout() {
  const { user, logout } = useAuthStore();
  const navigate = useNavigate();
  const location = useLocation();
  const [mobileOpen, setMobileOpen] = useState(false);

  const title =
    [...pageTitles].reverse().find((entry) => location.pathname.startsWith(entry.match))?.title ??
    'DisasterAid';

  const handleLogout = async () => {
    await logout();
    navigate('/login', { replace: true });
  };

  return (
    <div className="flex h-full">
      {/* Desktop sidebar */}
      <aside className="hidden w-64 shrink-0 border-r border-slate-200 bg-white lg:block">
        <SidebarContent />
      </aside>

      {/* Mobile sidebar */}
      {mobileOpen && (
        <div className="fixed inset-0 z-40 lg:hidden">
          <div
            className="absolute inset-0 bg-slate-900/50"
            onClick={() => setMobileOpen(false)}
            aria-hidden="true"
          />
          <aside className="absolute left-0 top-0 h-full w-64 bg-white shadow-xl">
            <button
              type="button"
              onClick={() => setMobileOpen(false)}
              className="absolute right-3 top-4 rounded-lg p-1.5 text-slate-500 hover:bg-slate-100"
              aria-label="Close menu"
            >
              <X className="h-5 w-5" />
            </button>
            <SidebarContent onNavigate={() => setMobileOpen(false)} />
          </aside>
        </div>
      )}

      <div className="flex min-w-0 flex-1 flex-col">
        <header className="flex h-16 items-center justify-between gap-4 border-b border-slate-200 bg-white px-4 sm:px-6">
          <div className="flex items-center gap-3">
            <button
              type="button"
              onClick={() => setMobileOpen(true)}
              className="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
              aria-label="Open menu"
            >
              <Menu className="h-5 w-5" />
            </button>
            <h1 className="text-lg font-semibold text-slate-900">{title}</h1>
          </div>

          <div className="flex items-center gap-3">
            <NavLink
              to="/profile"
              className="flex items-center gap-2 rounded-lg px-2 py-1.5 transition hover:bg-slate-100"
            >
              <div className="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-xs font-semibold text-primary-700">
                {initials(user?.name)}
              </div>
              <div className="hidden text-left sm:block">
                <p className="text-sm font-medium text-slate-900">{user?.name ?? '—'}</p>
                <p className="text-xs text-slate-500">{user?.roles?.[0] ?? '—'}</p>
              </div>
            </NavLink>

            <button
              type="button"
              onClick={handleLogout}
              className="btn-secondary px-3 py-1.5 text-xs"
            >
              <LogOut className="h-4 w-4" />
              Sign out
            </button>
          </div>
        </header>

        <main className="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
