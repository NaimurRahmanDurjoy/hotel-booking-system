import os

# XML Template for Draw.io
xml_template = """<mxfile>
  <diagram name="ER Diagram">
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

def ent(id, val, x, y):
    safe_val = escape_xml(val)
    return f'        <mxCell id="ent_{id}" value="{safe_val}" style="whiteSpace=wrap;html=1;align=center;strokeWidth=2;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="120" height="60" as="geometry" /></mxCell>\n'

def rel_er(id, val, x, y):
    safe_val = escape_xml(val)
    return f'        <mxCell id="rel_{id}" value="{safe_val}" style="rhombus;whiteSpace=wrap;html=1;align=center;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="100" height="80" as="geometry" /></mxCell>\n'

def attr(id, val, x, y):
    safe_val = escape_xml(val)
    return f'        <mxCell id="attr_{id}" value="{safe_val}" style="ellipse;whiteSpace=wrap;html=1;align=center;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="100" height="40" as="geometry" /></mxCell>\n'

def conn(id, s, t, label=""):
    safe_label = escape_xml(label)
    return f'        <mxCell id="conn_{id}" value="{safe_label}" style="endArrow=none;html=1;rounded=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="{s}" target="{t}"><mxGeometry relative="1" as="geometry" /></mxCell>\n'

def generate_er_diagram():
    c = ""
    # Entities
    c += ent("customer", "Customer", 100, 300)
    c += ent("booking", "Booking", 400, 300)
    c += ent("room", "Room", 750, 300)
    c += ent("hotel", "Hotel", 750, 100)
    c += ent("manager", "Manager", 400, 100)
    c += ent("admin", "Admin", 100, 100)
    
    # Relationships
    c += rel_er("makes", "Makes", 250, 290)
    c += rel_er("includes", "Includes", 580, 290)
    c += rel_er("belongs", "Belongs to", 760, 200)
    c += rel_er("manages_h", "Manages", 580, 100)
    c += rel_er("manages_u", "Manages", 250, 100)
    
    # Attributes
    c += attr("c_id", "Customer_ID", 20, 220)
    c += attr("c_name", "Name", 130, 220)
    c += attr("b_id", "Booking_ID", 410, 380)
    c += attr("r_num", "Room_Number", 760, 380)
    
    # Connections (Using the generated IDs)
    c += conn("1", "ent_customer", "rel_makes", "1")
    c += conn("2", "rel_makes", "ent_booking", "N")
    c += conn("3", "ent_booking", "rel_includes", "1")
    c += conn("4", "rel_includes", "ent_room", "N")
    c += conn("5", "ent_room", "rel_belongs", "N")
    c += conn("6", "rel_belongs", "ent_hotel", "1")
    c += conn("7", "ent_manager", "rel_manages_h", "1")
    c += conn("8", "rel_manages_h", "ent_hotel", "N")
    c += conn("9", "ent_admin", "rel_manages_u", "1")
    c += conn("10", "rel_manages_u", "ent_customer", "N")
    
    # Attr connections
    c += conn("a1", "ent_customer", "attr_c_id")
    c += conn("a2", "ent_customer", "attr_c_name")
    c += conn("a3", "ent_booking", "attr_b_id")
    c += conn("a4", "ent_room", "attr_r_num")
    
    return xml_template.replace("{content}", c)

with open("Grand_Azure_ER_Diagram.drawio", "w") as f:
    f.write(generate_er_diagram())
