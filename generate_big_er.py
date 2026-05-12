import os

# XML Template for Draw.io
xml_template = """<mxfile>
  <diagram name="ER Diagram">
    <mxGraphModel dx="2000" dy="2000" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="1600" pageHeight="1200" math="0" shadow="0">
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
    return f'        <mxCell id="ent_{id}" value="{escape_xml(val)}" style="whiteSpace=wrap;html=1;align=center;strokeWidth=2;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="120" height="60" as="geometry" /></mxCell>\n'

def rel_er(id, val, x, y):
    return f'        <mxCell id="rel_{id}" value="{escape_xml(val)}" style="rhombus;whiteSpace=wrap;html=1;align=center;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="100" height="80" as="geometry" /></mxCell>\n'

def attr(id, val, x, y):
    return f'        <mxCell id="attr_{id}" value="{escape_xml(val)}" style="ellipse;whiteSpace=wrap;html=1;align=center;fillColor=#fff2cc;strokeColor=#d6b656;" vertex="1" parent="1"><mxGeometry x="{x}" y="{y}" width="100" height="40" as="geometry" /></mxCell>\n'

def conn(id, s, t, label=""):
    return f'        <mxCell id="conn_{id}" value="{escape_xml(label)}" style="endArrow=none;html=1;rounded=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="{s}" target="{t}"><mxGeometry relative="1" as="geometry" /></mxCell>\n'

def generate_big_er():
    c = ""
    # 1. Customer
    c += ent("customer", "Customer", 400, 400)
    c += attr("c1", "Customer_ID", 280, 320); c += conn("ac1", "ent_customer", "attr_c1")
    c += attr("c2", "Name", 280, 400); c += conn("ac2", "ent_customer", "attr_c2")
    c += attr("c3", "Email", 280, 480); c += conn("ac3", "ent_customer", "attr_c3")
    c += attr("c4", "Phone", 400, 320); c += conn("ac4", "ent_customer", "attr_c4")
    c += attr("c5", "Address", 520, 320); c += conn("ac5", "ent_customer", "attr_c5")
    c += attr("c6", "Balance", 520, 400); c += conn("ac6", "ent_customer", "attr_c6")
    
    # 2. Booking
    c += ent("booking", "Booking", 800, 400)
    c += attr("b1", "Booking_ID", 800, 300); c += conn("ab1", "ent_booking", "attr_b1")
    c += attr("b2", "Check_In", 920, 320); c += conn("ab2", "ent_booking", "attr_b2")
    c += attr("b3", "Check_Out", 920, 400); c += conn("ab3", "ent_booking", "attr_b3")
    c += attr("b4", "Total_Amount", 920, 480); c += conn("ab4", "ent_booking", "attr_b4")
    c += attr("b5", "Status", 800, 500); c += conn("ab5", "ent_booking", "attr_b5")
    
    # 3. Room
    c += ent("room", "Room", 1200, 400)
    c += attr("r1", "Room_ID", 1200, 300); c += conn("ar1", "ent_room", "attr_r1")
    c += attr("r2", "Room_No", 1320, 320); c += conn("ar2", "ent_room", "attr_r2")
    c += attr("r3", "Type", 1320, 400); c += conn("ar3", "ent_room", "attr_r3")
    c += attr("r4", "Price", 1320, 480); c += conn("ar4", "ent_room", "attr_r4")
    
    # 4. Hotel
    c += ent("hotel", "Hotel", 1200, 100)
    c += attr("h1", "Hotel_ID", 1080, 20); c += conn("ah1", "ent_hotel", "attr_h1")
    c += attr("h2", "Name", 1200, 20); c += conn("ah2", "ent_hotel", "attr_h2")
    c += attr("h3", "Location", 1320, 20); c += conn("ah3", "ent_hotel", "attr_h3")
    
    # 5. Manager
    c += ent("manager", "Manager", 800, 100)
    c += attr("m1", "Manager_ID", 680, 20); c += conn("am1", "ent_manager", "attr_m1")
    c += attr("m2", "Name", 800, 20); c += conn("am2", "ent_manager", "attr_m2")
    c += attr("m3", "Email", 920, 20); c += conn("am3", "ent_manager", "attr_m3")
    
    # 6. Admin
    c += ent("admin", "Admin", 400, 100)
    c += attr("ad1", "Admin_ID", 280, 20); c += conn("aad1", "ent_admin", "attr_ad1")
    c += attr("ad2", "Name", 400, 20); c += conn("aad2", "ent_admin", "attr_ad2")
    
    # Relationships
    c += rel_er("makes", "Makes", 600, 390); c += conn("r1", "ent_customer", "rel_makes", "1"); c += conn("r2", "rel_makes", "ent_booking", "N")
    c += rel_er("includes", "Includes", 1000, 390); c += conn("r3", "ent_booking", "rel_includes", "1"); c += conn("r4", "rel_includes", "ent_room", "N")
    c += rel_er("belongs", "Belongs", 1210, 250); c += conn("r5", "ent_room", "rel_belongs", "N"); c += conn("r6", "rel_belongs", "ent_hotel", "1")
    c += rel_er("manages_h", "Manages", 1000, 90); c += conn("r7", "ent_manager", "rel_manages_h", "1"); c += conn("r8", "rel_manages_h", "ent_hotel", "N")
    c += rel_er("manages_u", "Manages", 410, 250); c += conn("r9", "ent_admin", "rel_manages_u", "1"); c += conn("r10", "rel_manages_u", "ent_customer", "N")
    
    return xml_template.replace("{content}", c)

with open("Grand_Azure_ER_Diagram.drawio", "w") as f:
    f.write(generate_big_er())
