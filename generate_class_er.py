import os

# XML Template for Draw.io
xml_template = """<mxfile>
  <diagram name="{name}">
    <mxGraphModel dx="1200" dy="1200" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="1169" pageHeight="827" math="0" shadow="0">
      <root>
        <mxCell id="0" />
        <mxCell id="1" parent="0" />
{content}
      </root>
    </mxGraphModel>
  </diagram>
</mxfile>"""

def escape_xml(text):
    return text.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;').replace('"', '&quot;').replace("'", '&apos;')

def class_node(id, name, attrs, methods, x, y):
    h_header = 30
    h_attrs = len(attrs) * 20 + 10
    h_methods = len(methods) * 20 + 10
    total_h = h_header + h_attrs + h_methods
    w = 200
    
    # Header
    c = f'        <mxCell id="{id}" value="{escape_xml(name)}" style="swimlane;fontStyle=1;align=center;verticalAlign=top;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=0;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=1;marginBottom=0;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="{w}" height="{total_h}" as="geometry" /></mxCell>\n'
    
    # Attributes
    attr_text = "\\n".join([f"+ {a}" for a in attrs])
    c += f'        <mxCell id="{id}_a" value="{escape_xml(attr_text)}" style="text;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;" vertex="1" parent="{id}"><mxGeometry y="30" width="{w}" height="{h_attrs}" as="geometry" /></mxCell>\n'
    
    # Separator
    c += f'        <mxCell id="{id}_s" value="" style="line;strokeWidth=1;fillColor=none;align=left;verticalAlign=middle;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;" vertex="1" parent="{id}"><mxGeometry y="{30+h_attrs}" width="{w}" height="8" as="geometry" /></mxCell>\n'
    
    # Methods
    meth_text = "\\n".join([f"+ {m}" for m in methods])
    c += f'        <mxCell id="{id}_m" value="{escape_xml(meth_text)}" style="text;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;" vertex="1" parent="{id}"><mxGeometry y="{38+h_attrs}" width="{w}" height="{h_methods}" as="geometry" /></mxCell>\n'
    
    return c

def generate_class_diagram():
    c = ""
    # 1. User
    c += class_node("u", "User", ["mobile: string", "NID: string", "email: string", "balance: float"], ["register()", "login()", "updateProfile()"], 400, 300)
    # 2. Booking
    c += class_node("b", "Booking", ["bookingID: int", "checkIn: date", "total: float"], ["confirm()", "cancel()"], 100, 300)
    # 3. Hotel
    c += class_node("h", "Hotel", ["hotelID: int", "name: string", "location: string"], ["getRooms()"], 400, 50)
    # 4. Room
    c += class_node("r", "Room", ["roomNum: int", "type: string", "price: float"], ["checkAvailability()"], 100, 50)
    # 5. System
    c += class_node("sys", "System", ["version: string"], ["processPayment()", "verify()"], 700, 300)
    # 6. Admin
    c += class_node("adm", "Admin", ["adminID: int"], ["manageUsers()", "approveHotels()"], 700, 50)

    # Relationships (Simple lines with multiplicities)
    def rel(id, source, target, label, mult_s, mult_t, style="endArrow=none;"):
        return f'        <mxCell id="{id}" value="{escape_xml(label)}" style="{style};edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="{source}" target="{target}"><mxGeometry relative="1" as="geometry"><mxPoint as="offset" /><mxCell id="{id}_s" value="{mult_s}" style="edgeLabel;resizable=0;html=1;align=left;verticalAlign=bottom;" connectable="0" vertex="1" parent="{id}"><mxGeometry x="-1" relative="1" as="geometry"><mxPoint x="10" y="-10" as="offset" /></mxGeometry></mxCell><mxCell id="{id}_t" value="{mult_t}" style="edgeLabel;resizable=0;html=1;align=right;verticalAlign=bottom;" connectable="0" vertex="1" parent="{id}"><mxGeometry x="1" relative="1" as="geometry"><mxPoint x="-10" y="-10" as="offset" /></mxGeometry></mxCell></mxGeometry></mxCell>\n'

    c += rel("r1", "u", "b", "makes", "1", "0..*")
    c += rel("r2", "b", "r", "includes", "0..*", "1")
    c += rel("r3", "r", "h", "belongs to", "0..*", "1")
    c += rel("r4", "h", "adm", "managed by", "0..*", "1")
    c += rel("r5", "u", "sys", "registers", "*", "1", "endArrow=diamond;endFill=0;")
    
    return xml_template.replace("{name}", "Class Diagram").replace("{content}", c)

def generate_er_diagram():
    # Simplistic ER with entities, diamonds, and ovals
    def ent(id, val, x, y): return f'        <mxCell id="{id}" value="{escape_xml(val)}" style="whiteSpace=wrap;html=1;align=center;strokeWidth=2;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="120" height="60" as="geometry" /></mxCell>\n'
    def rel_er(id, val, x, y): return f'        <mxCell id="{id}" value="{escape_xml(val)}" style="rhombus;whiteSpace=wrap;html=1;align=center;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="100" height="80" as="geometry" /></mxCell>\n'
    def attr(id, val, x, y): return f'        <mxCell id="{id}" value="{escape_xml(val)}" style="ellipse;whiteSpace=wrap;html=1;align=center;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="100" height="40" as="geometry" /></mxCell>\n'
    def conn(id, s, t, label=""): return f'        <mxCell id="{id}" value="{escape_xml(label)}" style="endArrow=none;html=1;rounded=0;" edge="1" parent="1" source="{s}" target="{t}"><mxGeometry relative="1" as="geometry" /></mxCell>\n'

    c = ""
    # Entities
    c += ent("customer", "Customer", 100, 300)
    c += ent("booking", "Booking", 400, 300)
    c += ent("room", "Room", 700, 300)
    c += ent("admin", "Admin", 400, 550)
    
    # Relationships
    c += rel_er("makes", "Makes", 250, 290)
    c += rel_er("includes", "Includes", 550, 290)
    c += rel_er("manages", "Manages", 410, 430)
    
    # Attributes for Customer
    c += attr("c_id", "Customer_ID", 20, 220)
    c += attr("c_name", "Name", 130, 220)
    c += attr("c_mail", "Email", 20, 380)
    
    # Connections
    c += conn("e1", "customer", "makes", "1")
    c += conn("e2", "makes", "booking", "N")
    c += conn("e3", "booking", "includes", "1")
    c += conn("e4", "includes", "room", "N")
    c += conn("e5", "admin", "manages", "1")
    c += conn("e6", "manages", "booking", "N")
    
    c += conn("a1", "customer", "c_id")
    c += conn("a2", "customer", "c_name")
    c += conn("a3", "customer", "c_mail")
    
    return xml_template.replace("{name}", "ER Diagram").replace("{content}", c)

# Write files
with open("Grand_Azure_Class_Diagram.drawio", "w") as f:
    f.write(generate_class_diagram())
with open("Grand_Azure_ER_Diagram.drawio", "w") as f:
    f.write(generate_er_diagram())
