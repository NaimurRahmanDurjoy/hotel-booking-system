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

# 1. CLASS DIAGRAM GENERATOR (High Fidelity)
def class_node_ref(id, name, attrs, methods, x, y, header_color="#ffffff"):
    h_header = 35
    h_attrs = len(attrs) * 18 + 10
    h_methods = len(methods) * 18 + 10
    total_h = h_header + h_attrs + h_methods
    w = 200
    
    # Header (Colored as per ref)
    c = f'        <mxCell id="{id}" value="{escape_xml(name)}" style="swimlane;fontStyle=1;align=center;verticalAlign=top;childLayout=stackLayout;horizontal=1;startSize=35;horizontalStack=0;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=1;marginBottom=0;html=1;fillColor={header_color};strokeColor=#000000;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="{w}" height="{total_h}" as="geometry" /></mxCell>\n'
    
    # Attributes
    attr_text = "&lt;br&gt;".join([f"+ {a}" for a in attrs])
    c += f'        <mxCell id="{id}_a" value="{attr_text}" style="text;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;html=1;" vertex="1" parent="{id}"><mxGeometry y="35" width="{w}" height="{h_attrs}" as="geometry" /></mxCell>\n'
    
    # Separator
    c += f'        <mxCell id="{id}_s" value="" style="line;strokeWidth=1;fillColor=none;align=left;verticalAlign=middle;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;" vertex="1" parent="{id}"><mxGeometry y="{35+h_attrs}" width="{w}" height="8" as="geometry" /></mxCell>\n'
    
    # Methods
    meth_text = "&lt;br&gt;".join([f"+ {m}" for m in methods])
    c += f'        <mxCell id="{id}_m" value="{meth_text}" style="text;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;html=1;" vertex="1" parent="{id}"><mxGeometry y="{43+h_attrs}" width="{w}" height="{h_methods}" as="geometry" /></mxCell>\n'
    
    return c

def generate_ref_class():
    c = ""
    # Color coding as per ref: User/Subscription are yellow
    yellow = "#fff2cc"
    blue = "#dae8fc"
    
    c += class_node_ref("ticket", "Booking/Ticket", ["bookingID: int", "hotelName: string", "checkIn: date", "price: float"], ["bookTicket()"], 30, 30)
    c += class_node_ref("service", "Hotel Service", ["hotelID: int", "name: string", "location: string", "rating: float"], ["getDetails()", "getRooms()"], 430, 30)
    c += class_node_ref("admin", "Admin", ["name: string", "email: string", "phone: string"], ["addHotel()", "manageUsers()"], 830, 30)
    c += class_node_ref("user", "User", ["mobileNumber: string", "NID: string", "email: string", "password: string", "balance: float"], ["register()", "login()", "resetPassword()", "updateProfile()", "submitFeedback()"], 430, 350, yellow)
    c += class_node_ref("pass", "Subscription Plan", ["planName: string", "price: float", "expiry: date"], ["recharge()", "manageBalance()"], 30, 350, yellow)
    c += class_node_ref("system", "System", [], ["verifyCredentials()", "deductFare()", "notify()"], 830, 350, blue)
    
    # Relationships with diamonds and multiplicities
    def rel(id, source, target, label, mult_s, mult_t, style="endArrow=none;"):
        return f'        <mxCell id="{id}" value="{escape_xml(label)}" style="{style};edgeStyle=orthogonalEdgeStyle;rounded=0;" edge="1" parent="1" source="{source}" target="{target}"><mxGeometry relative="1" as="geometry"><mxPoint as="offset" /><mxCell id="{id}_s" value="{mult_s}" style="edgeLabel;resizable=0;html=1;align=left;verticalAlign=bottom;" connectable="0" vertex="1" parent="{id}"><mxGeometry x="-1" relative="1" as="geometry"><mxPoint x="10" y="-10" as="offset" /></mxGeometry></mxCell><mxCell id="{id}_t" value="{mult_t}" style="edgeLabel;resizable=0;html=1;align=right;verticalAlign=bottom;" connectable="0" vertex="1" parent="{id}"><mxGeometry x="1" relative="1" as="geometry"><mxPoint x="-10" y="-10" as="offset" /></mxGeometry></mxCell></mxGeometry></mxCell>\n'

    c += rel("r1", "ticket", "service", "Depends on", "", "")
    c += rel("r2", "service", "admin", "Manages", "0..*", "1..2")
    c += rel("r3", "ticket", "user", "Purchase", "0..*", "1")
    c += rel("r4", "user", "pass", "Purchase", "1", "1", "endArrow=diamond;endFill=0;")
    c += rel("r5", "user", "system", "Register", "*", "1", "endArrow=diamond;endFill=0;")
    c += rel("r6", "admin", "system", "Run", "1..2", "1", "endArrow=diamond;endFill=1;")
    
    return xml_template.replace("{name}", "Class Diagram").replace("{content}", c)

# 2. ER DIAGRAM GENERATOR (High Fidelity)
def generate_ref_er():
    def ent(id, val, x, y, double=False):
        style = "whiteSpace=wrap;html=1;align=center;strokeWidth=2;"
        if double: style += "shape=doubleRectangle;"
        return f'        <mxCell id="ent_{id}" value="{escape_xml(val)}" style="{style}" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="120" height="60" as="geometry" /></mxCell>\n'
    
    def rel_er(id, val, x, y, double=False):
        style = "rhombus;whiteSpace=wrap;html=1;align=center;"
        if double: style += "shape=doubleRhombus;"
        return f'        <mxCell id="rel_{id}" value="{escape_xml(val)}" style="{style}" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="100" height="80" as="geometry" /></mxCell>\n'
    
    def attr(id, val, x, y):
        return f'        <mxCell id="attr_{id}" value="{escape_xml(val)}" style="ellipse;whiteSpace=wrap;html=1;align=center;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="100" height="40" as="geometry" /></mxCell>\n'
    
    def conn(id, s, t, label=""):
        return f'        <mxCell id="conn_{id}" value="{escape_xml(label)}" style="endArrow=none;html=1;rounded=0;" edge="1" parent="1" source="{s}" target="{t}"><mxGeometry relative="1" as="geometry" /></mxCell>\n'

    c = ""
    # Layout based on ref 8.2
    c += ent("customer", "Customer", 450, 250)
    c += ent("payment", "Payment", 750, 250, True) # Double border
    c += ent("admin", "Admin", 450, 500)
    c += ent("feedback", "Feedback", 150, 500, True)
    
    # Attributes in fan pattern
    c += attr("c1", "Customer_ID", 460, 150); c += conn("ac1", "ent_customer", "attr_c1")
    c += attr("c2", "Customer_name", 560, 150); c += conn("ac2", "ent_customer", "attr_c2")
    c += attr("c3", "Customer_mail", 360, 150); c += conn("ac3", "ent_customer", "attr_c3")
    
    c += attr("p1", "Payment_ID", 750, 150); c += conn("ap1", "ent_payment", "attr_p1")
    c += attr("p2", "Amount", 860, 150); c += conn("ap2", "ent_payment", "attr_p2")
    
    # Relationships
    c += rel_er("makes", "makes", 630, 240); c += conn("r1", "ent_customer", "rel_makes", "1"); c += conn("r2", "rel_makes", "ent_payment", "1")
    c += rel_er("supports", "supports", 460, 380); c += conn("r3", "ent_customer", "rel_supports", "N"); c += conn("r4", "rel_supports", "ent_admin", "1")
    c += rel_er("views", "Views", 300, 500); c += conn("r5", "ent_admin", "rel_views", "1"); c += conn("r6", "rel_views", "ent_feedback", "M")
    
    return xml_template.replace("{name}", "ER Diagram").replace("{content}", c)

# Write files
with open("Grand_Azure_Class_Diagram.drawio", "w") as f:
    f.write(generate_ref_class())
with open("Grand_Azure_ER_Diagram.drawio", "w") as f:
    f.write(generate_ref_er())
