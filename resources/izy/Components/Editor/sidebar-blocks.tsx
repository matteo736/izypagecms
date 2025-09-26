import { cn } from "@/lib/utils";
import { htmlTagItems } from "../Blocks/htmltags";

type SidebarBlockProps = {
  icon: React.ElementType;
  label: string;
};

function SidebarBlock({ icon: Icon, label }: SidebarBlockProps) {
  return (
    <div
      className={cn(
        "flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-muted-foreground transition-all hover:bg-muted cursor-pointer"
      )}
    >
      <Icon className="h-4 w-4" />
      {label}
    </div>
  );
}

export function SidebarListBlocks() {
  return (
    <div className="flex h-screen w-64 flex-col gap-4 border-r bg-background p-4 overflow-y-auto">
      <h2 className="text-sm font-semibold text-muted-foreground mb-2">Elementi HTML</h2>
      <div className="grid grid-cols-1 gap-1 ">
        {htmlTagItems.map(({ tag, label, icon: Icon }) => (
          <SidebarBlock key={tag} label={label} icon={Icon} />
        ))}
      </div>
    </div>
  );
}



