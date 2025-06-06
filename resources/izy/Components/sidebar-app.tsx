import { Calendar, Home, Inbox, Search, Settings, Notebook, User } from "lucide-react"

import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/Components/ui/sidebar"
import { Link } from "@inertiajs/react"


// Menu items.
const items = [
  {
    title: "Home",
    url: route('izy.admin'),
    icon: Home,
  },
  {
    title: "Pages",
    url: route('pages.all'),
    icon: Notebook,
  },
  {
    title: "Calendar",
    url: "#",
    icon: Calendar,
  },
  {
    title: "Search",
    url: "#",
    icon: Search,
  },
  {
    title: "Settings",
    url: "#",
    icon: Settings,
  },
]

export const AppSidebar = () => {
  return (
    <Sidebar variant="sidebar" collapsible="icon" className="z-50" >
      <SidebarContent className="bg-secondary">
        <SidebarGroup>
          <SidebarGroupLabel>Application</SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              {items.map((item) => (
                <SidebarMenuItem key={item.title}>
                  <SidebarMenuButton asChild>
                    <Link href={item.url} as="button">
                      <item.icon />
                      <span>{item.title}</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
      </SidebarContent>
      <SidebarFooter className="bg-secondary">
        <SidebarContent>
          <SidebarGroupContent>
            <SidebarMenu>
              <SidebarMenuItem key={'logout'}>
                <SidebarMenuButton asChild variant="destructive" size="sm" >
                  <Link href={route('logout')} method="post" as="button">
                    <User />
                    <span>Logout</span>
                  </Link>
                </SidebarMenuButton>
              </SidebarMenuItem>
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarContent>
      </SidebarFooter>
    </Sidebar>
  )
};