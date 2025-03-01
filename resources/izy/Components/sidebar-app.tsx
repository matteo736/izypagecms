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
import { Button } from "./ui/button"

// Menu items.
const items = [
  {
    title: "Home",
    url: route('izy.admin'),
    icon: Home,
  },
  {
    title: "Pages",
    url: route('pages'),
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

export function AppSidebar() {
  return (
    <Sidebar variant="sidebar" collapsible="icon" className="z-50" >
      <SidebarContent>
        <SidebarGroup>
          <SidebarGroupLabel>Application</SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              {items.map((item) => (
                <SidebarMenuItem key={item.title}>
                  <SidebarMenuButton asChild>
                    <Link href={item.url}>
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
      <SidebarFooter>
        <SidebarContent>
          <Button variant="destructive" size="sm">
            <Link href={route('logout')} method="post">
              <User />
            </Link>
          </Button>
        </SidebarContent>
      </SidebarFooter>
    </Sidebar>
  )
}

