import { htmlTagItems } from "@/Components/Blocks/htmltags";
import { ScrollArea } from "@/components/ui/scroll-area";
import { cn } from "@/lib/utils";
import { Icon as LucideIcon } from "lucide-react";

export function SidebarTags() {
  return (
    <div className="space-y-1">
      <h2 className="px-4 text-xs font-semibold text-muted-foreground">
        HTML ELEMENTS
      </h2>
      <ScrollArea className="h-[350px] px-2">
        <div className="flex flex-col gap-1 py-1">
          {htmlTagItems.map(({ tag, label, icon: Icon }) => (
            <SidebarItem key={tag} icon={Icon} label={label} tag={tag} />
          ))}
        </div>
      </ScrollArea>
    </div>
  );
}

type SidebarItemProps = {
  icon: React.ComponentType<React.ComponentProps<typeof LucideIcon>>;
  label: string;
  tag: string;
};

function SidebarItem({ icon: Icon, label, tag }: SidebarItemProps) {
  return (
    <div
      className={cn(
        "group flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-muted-foreground transition-all hover:bg-muted cursor-grab"
      )}
      draggable
      onDragStart={(e) => {
        e.dataTransfer.setData("text/plain", tag);
      }}
    >
      <Icon className="h-4 w-4"/>
      {label}
    </div>
  );
}
